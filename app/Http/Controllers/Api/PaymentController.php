<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Subscription, Semester, AppConfig};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Http, Log};
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function subscriptionPrice()
    {
        $amount = $this->subscriptionAmount();
        $price = $amount / 100;

        return response()->json([
            'success' => true,
            'amount' => $amount,
            'price' => $price,
            'formatted_price' => 'Rs. ' . number_format($price, 2),
            'currency' => 'INR',
        ]);
    }

    public function createOrder(Request $request)
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);

        $user = $request->user();

        $existing = Subscription::where('user_id', $user->id)
            ->where('semester_id', $request->semester_id)
            ->where('status', 'paid')->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active subscription.',
            ], 409);
        }

        $amount = $this->subscriptionAmount();

        try {
            $response = Http::withBasicAuth(
                config('services.razorpay.key_id'),
                config('services.razorpay.key_secret')
            )->withoutVerifying()
            ->timeout(30)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount'   => $amount,
                'currency' => 'INR',
                'receipt'  => 'yd_' . $user->id . '_' . $request->semester_id . '_' . time(),
                'notes'    => ['user_id' => $user->id, 'semester_id' => $request->semester_id],
            ]);

            if (!$response->successful()) {
                Log::error('Razorpay API Error', ['response' => $response->body()]);
                return response()->json(['success' => false, 'message' => 'Payment initiation failed.'], 500);
            }

            $order = $response->json();

            Subscription::updateOrCreate(
                ['user_id' => $user->id, 'semester_id' => $request->semester_id],
                ['razorpay_order_id' => $order['id'], 'amount' => $amount, 'status' => 'pending']
            );

            return response()->json([
                'success'      => true,
                'order_id'     => $order['id'],
                'amount'       => $amount,
                'currency'     => 'INR',
                'key_id'       => config('services.razorpay.key_id'),
                'user_name'    => $user->name,
                'user_mobile'  => $user->mobile,
                'user_email'   => $user->email ?? '',
                'description'  => 'YD App Semester Subscription',
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment order. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature'  => 'required',
            'semester_id'         => 'required|exists:semesters,id',
        ]);

        $user = $request->user();
        $keySecret = config('services.razorpay.key_secret');

        $generatedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            $keySecret
        );

        if ($generatedSignature !== $request->razorpay_signature) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 422);
        }

        $semester  = Semester::find($request->semester_id);
        $expiresAt = $semester->end_date ? Carbon::parse($semester->end_date)->endOfDay() : null;

        Subscription::where('user_id', $user->id)
            ->where('semester_id', $request->semester_id)
            ->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
                'status'              => 'paid',
                'paid_at'             => Carbon::now(),
                'expires_at'          => $expiresAt,
            ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Payment successful! All subjects are now unlocked.',
            'expires_at' => $expiresAt?->toDateString(),
        ]);
    }

    public function checkSubscription(Request $request, $semesterId)
    {
        $user = $request->user();
        $subscription = Subscription::where('user_id', $user->id)
            ->where('semester_id', $semesterId)
            ->where('status', 'paid')->first();

        $isActive = $subscription && (
            is_null($subscription->expires_at) ||
            $subscription->expires_at > Carbon::now()
        );

        return response()->json([
            'success'    => true,
            'is_active'  => $isActive,
            'expires_at' => $subscription?->expires_at?->toDateString(),
        ]);
    }

    public function webhook(Request $request)
    {
        $webhookSecret = config('services.razorpay.webhook_secret');
        $signature     = $request->header('X-Razorpay-Signature');
        $payload       = $request->getContent();
        $generated     = hash_hmac('sha256', $payload, $webhookSecret);

        if ($signature !== $generated) {
            return response()->json(['status' => 'invalid'], 400);
        }

        if ($request->input('event') === 'payment.captured') {
            $orderId = $request->input('payload.payment.entity.order_id');
            $paymentId = $request->input('payload.payment.entity.id');
            $sub = Subscription::where('razorpay_order_id', $orderId)->first();
            if ($sub && $sub->status !== 'paid') {
                $sub->update(['razorpay_payment_id' => $paymentId, 'status' => 'paid', 'paid_at' => Carbon::now()]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    private function subscriptionAmount(): int
    {
        return max(100, (int) AppConfig::getValue('subscription_price', 7500));
    }
}

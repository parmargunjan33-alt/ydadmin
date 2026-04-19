<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    // STEP 1: Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
        ]);

        if (User::where('mobile', $request->mobile)->where('mobile_verified', true)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number already registered. Please login.',
            ], 409);
        }

        $otp = rand(100000, 999999);
        Otp::where('mobile', $request->mobile)->delete();
        Otp::create([
            'mobile'     => $request->mobile,
            'otp'        => $otp,
            'purpose'    => 'register',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // FOR TESTING — shows OTP in response (remove before going live)
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
            'otp'     => $otp,
        ]);
    }

    // STEP 2: Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp'    => 'required|digits:6',
        ]);

        $otpRecord = Otp::where('mobile', $request->mobile)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 422);
        }

        $otpRecord->update(['is_used' => true]);
        $verifyToken = base64_encode($request->mobile . ':' . time());

        return response()->json([
            'success'      => true,
            'message'      => 'OTP verified successfully.',
            'verify_token' => $verifyToken,
        ]);
    }

    // STEP 3: Register
    public function register(Request $request)
    {
        $request->validate([
            'mobile'                => 'required|digits:10|unique:users,mobile',
            'verify_token'          => 'required',
            'name'                  => 'required|string|max:100',
            'email'                 => 'nullable|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
            'device_id'             => 'nullable|string|max:255',
            'device_name'           => 'nullable|string',
        ]);

        $deviceId = $this->resolveDeviceId($request, $request->mobile);

        $decoded = base64_decode($request->verify_token);
        $parts   = explode(':', $decoded);
        if ($parts[0] !== $request->mobile) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification token.',
            ], 422);
        }

        $user = User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'mobile'             => $request->mobile,
            'password'           => Hash::make($request->password),
            'device_id'          => $deviceId,
            'device_name'        => $request->device_name,
            'mobile_verified'    => true,
            'mobile_verified_at' => Carbon::now(),
            'is_active'          => true,
        ]);

        $token = $user->createToken('yd-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'token_type' => 'Bearer',
            'access_token' => $token,
            'token'   => $token,
            'server_token' => $token,
            'serve_token' => $token,
            'user'    => $this->userResponse($user),
        ]);
    }

    // STEP 4: Login
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'nullable|email|required_without:mobile',
            'mobile'    => 'nullable|digits:10|required_without:email',
            'password'  => 'required',
            'device_id' => 'nullable|string|max:255',
        ]);

        $user = User::query()
            ->when($request->filled('email'), fn ($query) => $query->where('email', $request->email))
            ->when($request->filled('mobile'), fn ($query) => $query->orWhere('mobile', $request->mobile))
            ->first();
        $deviceId = $this->resolveDeviceId(
            $request,
            $request->email ?: $request->mobile ?: $user?->email ?: $user?->mobile
        );

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials.',
            ], 401);
        }

        if ($user->is_active === false) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been disabled. Contact support.',
            ], 403);
        }

        // Single device lock check
        // if ($user->device_id && $user->device_id !== $deviceId) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Account is active on another device. Contact support to switch devices.',
        //         'error'   => 'device_mismatch',
        //     ], 403);
        // }

        $user->update([
            'device_id'   => $deviceId,
            'device_name' => $request->device_name ?? $user->device_name,
            'is_active'   => true,
        ]);

        $user->tokens()->delete();
        $token = $user->createToken('yd-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token_type' => 'Bearer',
            'access_token' => $token,
            'token'   => $token,
            'server_token' => $token,
            'serve_token' => $token,
            'user'    => $this->userResponse($user),
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
    }

    // Get Profile
    public function profile(Request $request)
    {
        $user = $request->user()->load(['university', 'course', 'semester']);
        return response()->json(['success' => true, 'user' => $this->userResponse($user)]);
    }

    private function userResponse($user)
    {
        return [
            'id'           => $user->id,
            'name'         => $user->name ?? '',
            'email'        => $user->email ?? '',
            'mobile'       => $user->mobile ?? '',
            'university_id'=> $user->university_id ?? 0,
            'course_id'    => $user->course_id ?? 0,
            'semester_id'  => $user->semester_id ?? 0,
            'university'   => $user->university?->name ?? '',
            'course'       => $user->course?->name ?? '',
            'semester'     => $user->semester?->label ?? '',
        ];
    }

    private function resolveDeviceId(Request $request, ?string $identifier = null): string
    {
        $deviceId = $request->input('device_id') ?: $request->header('X-Device-Id');

        if ($deviceId) {
            return $deviceId;
        }

        if (app()->isLocal()) {
            if ($identifier) {
                return 'local-dev-' . preg_replace('/[^A-Za-z0-9_-]/', '-', $identifier);
            }

            throw ValidationException::withMessages([
                'device_id' => ['The device_id field is required when login identifier is missing.'],
            ]);
        }

        throw ValidationException::withMessages([
            'device_id' => ['The device_id field is required.'],
        ]);
    }
}

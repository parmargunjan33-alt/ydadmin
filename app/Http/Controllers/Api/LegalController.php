<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use Illuminate\Http\Response;

class LegalController extends Controller
{
    /**
     * Get Terms and Conditions
     */
    public function getTermsAndConditions()
    {
        $termsAndConditions = AppConfig::getValue('terms_and_conditions', '');

        if (empty($termsAndConditions)) {
            return response()->json([
                'success' => false,
                'message' => 'Terms and Conditions not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'title' => 'Terms and Conditions',
            'content' => $termsAndConditions,
        ]);
    }

    /**
     * Get Privacy Policy
     */
    public function getPrivacyPolicy()
    {
        $privacyPolicy = AppConfig::getValue('privacy_policy', '');

        if (empty($privacyPolicy)) {
            return response()->json([
                'success' => false,
                'message' => 'Privacy Policy not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'title' => 'Privacy Policy',
            'content' => $privacyPolicy,
        ]);
    }

    /**
     * Get App Contact Information
     */
    public function getContactInfo()
    {
        $supportEmail = AppConfig::getValue('support_email', '');

        if (empty($supportEmail)) {
            return response()->json([
                'success' => false,
                'message' => 'Contact information not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'support_email' => $supportEmail,
        ]);
    }
}

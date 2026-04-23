# Forgot Password API Documentation

## Overview
The Forgot Password feature allows users to securely reset their password through an email-based OTP verification process. This follows a three-step workflow.

---

## API Endpoints

### 1. Send Forgot Password OTP
**Endpoint:** `POST /api/auth/forgot-password/send-otp`

**Description:** Sends an OTP to the user's registered email address for password reset.

**Request Body:**
```json
{
    "email": "user@example.com"
}
```

**Validation:**
- `email` - Required, must be a valid email format

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "OTP sent to your email successfully.",
    "otp": 123456
}
```

**Response (Email not found - 404):**
```json
{
    "success": false,
    "message": "No account found with this email address."
}
```

**Response (Validation error - 422):**
```json
{
    "success": false,
    "message": "The email field is required."
}
```

**Notes:**
- OTP is valid for **10 minutes**
- Previous OTPs for the same email are deleted
- For testing purposes, OTP is returned in the response
- In production, remove the `"otp"` field from the response
- Email is sent with an HTML template

---

### 2. Verify Forgot Password OTP
**Endpoint:** `POST /api/auth/forgot-password/verify-otp`

**Description:** Verifies the OTP sent to the user's email and returns a verification token.

**Request Body:**
```json
{
    "email": "user@example.com",
    "otp": "123456"
}
```

**Validation:**
- `email` - Required, must be a valid email format
- `otp` - Required, must be exactly 6 digits

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "OTP verified successfully.",
    "verify_token": "dXNlckBlbWFpbC5jb206MTcxNjQ3MzQ1"
}
```

**Response (Invalid OTP - 422):**
```json
{
    "success": false,
    "message": "Invalid or expired OTP."
}
```

**Response (Email not found - 404):**
```json
{
    "success": false,
    "message": "No account found with this email address."
}
```

**Notes:**
- OTP must be valid and not already used
- OTP must not be expired
- The returned `verify_token` is required for the next step
- Store this token securely on the client

---

### 3. Reset Password
**Endpoint:** `POST /api/auth/forgot-password/reset`

**Description:** Resets the user's password using the verification token and new password.

**Request Body:**
```json
{
    "email": "user@example.com",
    "verify_token": "dXNlckBlbWFpbC5jb206MTcxNjQ3MzQ1",
    "password": "newPassword123",
    "password_confirmation": "newPassword123"
}
```

**Validation:**
- `email` - Required, must be a valid email format
- `verify_token` - Required
- `password` - Required, minimum 6 characters
- `password_confirmation` - Required, must match `password`

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "Password reset successfully. Please login with your new password."
}
```

**Response (Invalid token - 422):**
```json
{
    "success": false,
    "message": "Invalid verification token."
}
```

**Response (Email not found - 404):**
```json
{
    "success": false,
    "message": "No account found with this email address."
}
```

**Notes:**
- All existing access tokens are invalidated after password reset
- User must login again with the new password
- Password is hashed using Laravel's Hash::make()

---

## Complete Workflow Example

### Step 1: Request Password Reset
```bash
curl -X POST http://localhost:8000/api/auth/forgot-password/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com"
  }'
```

**Response:**
```json
{
    "success": true,
    "message": "OTP sent to your email successfully.",
    "otp": 456789
}
```

### Step 2: Verify OTP
User receives OTP in email (456789) and submits it:

```bash
curl -X POST http://localhost:8000/api/auth/forgot-password/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "otp": "456789"
  }'
```

**Response:**
```json
{
    "success": true,
    "message": "OTP verified successfully.",
    "verify_token": "dXNlckBlbWFpbC5jb206MTcxNjQ3MzQ1"
}
```

### Step 3: Reset Password
Using the verify token from Step 2:

```bash
curl -X POST http://localhost:8000/api/auth/forgot-password/reset \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "verify_token": "dXNlckBlbWFpbC5jb206MTcxNjQ3MzQ1",
    "password": "newSecurePassword123",
    "password_confirmation": "newSecurePassword123"
  }'
```

**Response:**
```json
{
    "success": true,
    "message": "Password reset successfully. Please login with your new password."
}
```

### Step 4: Login with New Password
User can now login with the new password:

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "newSecurePassword123",
    "device_id": "device-id-123"
  }'
```

---

## Key Features

✅ **Three-step OTP verification process**
- User requests password reset
- Receives OTP via email
- Verifies OTP and gets verification token
- Resets password securely

✅ **Security Features**
- Passwords are hashed using Laravel's Hash::make()
- All existing tokens are invalidated after password reset
- OTP expires after 10 minutes
- Each OTP can only be used once
- Verification token is unique and time-based

✅ **Email Notifications**
- Styled HTML emails for OTP delivery
- Different email templates for different purposes
- Email sending errors are logged but don't block the API

✅ **Error Handling**
- Proper HTTP status codes (200, 404, 422)
- Descriptive error messages
- Validation of all inputs
- Comprehensive logging for debugging

✅ **Database Logging**
- All actions are logged to the applications logs
- Includes email, timestamp, and user details
- Helps track security events and debug issues

---

## Email Template

Users receive an email with the following structure:

```
YD App - Password Reset

Your One-Time Password (OTP) for password reset is:

[XXXXXX] (6-digit OTP displayed prominently)

This OTP will expire in 10 minutes

If you didn't request this code, please ignore this email and 
your password will remain unchanged.
```

---

## Error Codes & Messages

| Status | Error | Message |
|--------|-------|---------|
| 404 | Not Found | No account found with this email address. |
| 422 | Validation Error | Invalid or expired OTP. |
| 422 | Invalid Token | Invalid verification token. |
| 422 | Validation Error | The email field is required. |
| 422 | Validation Error | The otp field must be 6 digits. |
| 422 | Validation Error | Passwords do not match. |

---

## Frontend Implementation Guide

### React/Vue Example Flow

```javascript
// Step 1: Send OTP
async function sendForgotPasswordOtp(email) {
  const response = await fetch('/api/auth/forgot-password/send-otp', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email })
  });
  return response.json();
}

// Step 2: Verify OTP
async function verifyForgotPasswordOtp(email, otp) {
  const response = await fetch('/api/auth/forgot-password/verify-otp', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, otp })
  });
  return response.json();
}

// Step 3: Reset Password
async function resetPassword(email, verifyToken, password) {
  const response = await fetch('/api/auth/forgot-password/reset', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      email,
      verify_token: verifyToken,
      password,
      password_confirmation: password
    })
  });
  return response.json();
}
```

---

## Testing in Development

For testing purposes, the API returns the OTP in the response. **Important:** Remove the `"otp"` field from the response before deploying to production.

To remove from production:
1. Open [AuthController.php](app/Http/Controllers/Api/AuthController.php)
2. Find the `sendForgotPasswordOtp()` method
3. Remove the `'otp' => $otp,` line from the response

---

## Production Checklist

- [ ] Remove OTP from response in `sendForgotPasswordOtp()`
- [ ] Configure proper email sending (MAIL_* in .env)
- [ ] Test email template rendering
- [ ] Verify logging is properly configured
- [ ] Set up database backups
- [ ] Enable HTTPS for all endpoints
- [ ] Add rate limiting to prevent OTP abuse
- [ ] Monitor failed password reset attempts
- [ ] Test end-to-end workflow

---

## Additional Notes

- OTP validity: **10 minutes**
- Password minimum length: **6 characters**
- Email notifications use HTML formatting for better user experience
- All actions are logged for security auditing
- The system uses Laravel's `Hash::make()` for password encryption

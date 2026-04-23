# Forgot Password API - Quick Reference

## 🔐 Three-Step Password Reset Flow

### 1️⃣ Send OTP
```
POST /api/auth/forgot-password/send-otp
Content-Type: application/json

{
  "email": "user@example.com"
}

Response:
{
  "success": true,
  "message": "OTP sent to your email successfully.",
  "otp": 123456  // For testing only - remove in production
}
```

### 2️⃣ Verify OTP
```
POST /api/auth/forgot-password/verify-otp
Content-Type: application/json

{
  "email": "user@example.com",
  "otp": "123456"
}

Response:
{
  "success": true,
  "message": "OTP verified successfully.",
  "verify_token": "dXNlckBlbWFpbC5jb206MTcxNjQ3MzQ1"
}
```

### 3️⃣ Reset Password
```
POST /api/auth/forgot-password/reset
Content-Type: application/json

{
  "email": "user@example.com",
  "verify_token": "dXNlckBlbWFpbC5jb206MTcxNjQ3MzQ1",
  "password": "newPassword123",
  "password_confirmation": "newPassword123"
}

Response:
{
  "success": true,
  "message": "Password reset successfully. Please login with your new password."
}
```

---

## ⏱️ Key Details

| Property | Value |
|----------|-------|
| OTP Length | 6 digits |
| OTP Validity | 10 minutes |
| Password Min Length | 6 characters |
| Email Verification | Yes (HTML template) |
| Rate Limit | Not set (add before production) |
| Authentication | Not required (public endpoints) |

---

## 📝 Validation Rules

| Field | Rules |
|-------|-------|
| email | required, email format |
| otp | required, exactly 6 digits |
| password | required, minimum 6 chars, must match confirmation |
| password_confirmation | required, must match password |
| verify_token | required, valid format |

---

## ✅ Success Cases

- Email found and OTP sent ✓
- OTP verified successfully ✓
- Password reset and all tokens invalidated ✓

---

## ❌ Error Cases

| Status | Scenario |
|--------|----------|
| 404 | Email not found in system |
| 422 | Invalid/expired OTP |
| 422 | Invalid verification token |
| 422 | Validation errors |

---

## 🔒 Security Features

✓ OTP expires after 10 minutes
✓ OTP can only be used once
✓ All existing tokens invalidated on password reset
✓ Passwords hashed with Laravel Hash::make()
✓ Email notifications sent via SMTP
✓ All actions logged for audit trail

---

## 📂 Files Modified

- `app/Http/Controllers/Api/AuthController.php` - Added 3 methods
- `routes/api.php` - Added 3 public routes
- `FORGOT_PASSWORD_API.md` - Full documentation
- `FORGOT_PASSWORD_API_QUICK_REFERENCE.md` - This file

---

## 🚀 Testing Commands

### Using cURL
```bash
# Step 1
curl -X POST http://localhost:8000/api/auth/forgot-password/send-otp \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com"}'

# Step 2
curl -X POST http://localhost:8000/api/auth/forgot-password/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","otp":"123456"}'

# Step 3
curl -X POST http://localhost:8000/api/auth/forgot-password/reset \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","verify_token":"token","password":"newpass","password_confirmation":"newpass"}'
```

### Using Postman
1. Create new POST request to `http://localhost:8000/api/auth/forgot-password/send-otp`
2. Add JSON body with email
3. Send and get OTP
4. Use OTP in verify-otp endpoint
5. Use verify_token in reset endpoint

---

## 🛠️ Production Deployment Checklist

- [ ] Remove OTP from response (delete `'otp' => $otp,` line)
- [ ] Configure .env with real SMTP credentials
- [ ] Test email delivery
- [ ] Add rate limiting middleware
- [ ] Enable HTTPS
- [ ] Monitor logs for failed attempts
- [ ] Setup email templates on production server
- [ ] Test full workflow end-to-end
- [ ] Add CORS headers if needed
- [ ] Document for API consumers

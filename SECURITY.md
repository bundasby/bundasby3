# Security Policy - Bunda PAUD Kota Surabaya

## 🔒 Security Overview

Website Bunda PAUD Kota Surabaya menerapkan standar keamanan yang ketat untuk melindungi data pengguna dan integritas sistem.

---

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |
| < 1.0   | :x:                |

---

## Security Features

### Authentication & Authorization

| Feature                | Implementation                       |
| ---------------------- | ------------------------------------ |
| **Authentication**     | Laravel Sanctum (Token-based)        |
| **Password Hashing**   | Bcrypt (cost factor 12)              |
| **Session Management** | Secure HTTP-only cookies             |
| **Role-Based Access**  | Super Admin, Admin, Operator, Viewer |
| **Token Expiration**   | 24 hours (configurable)              |

### Data Protection

| Feature                    | Implementation                |
| -------------------------- | ----------------------------- |
| **Encryption at Rest**     | Database-level encryption     |
| **Encryption in Transit**  | TLS 1.3 (HTTPS)               |
| **Input Validation**       | Laravel Form Requests         |
| **Output Encoding**        | Vue.js automatic XSS escaping |
| **File Upload Validation** | MIME type + extension check   |

### Attack Prevention

| Attack Vector                         | Mitigation                                   |
| ------------------------------------- | -------------------------------------------- |
| **SQL Injection**                     | Eloquent ORM (parameterized queries)         |
| **XSS (Cross-Site Scripting)**        | Vue.js template escaping, CSP headers        |
| **CSRF (Cross-Site Request Forgery)** | Laravel CSRF tokens                          |
| **Clickjacking**                      | X-Frame-Options header                       |
| **Rate Limiting**                     | 60 requests/minute (API), 5 attempts (login) |

---

## Security Headers

```
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'
Referrer-Policy: strict-origin-when-cross-origin
```

---

## File Upload Security

### Allowed File Types

| Category     | Allowed Extensions              |
| ------------ | ------------------------------- |
| Images       | .jpg, .jpeg, .png, .gif, .webp  |
| Documents    | .pdf, .doc, .docx, .xls, .xlsx  |
| Maximum Size | 10MB (images), 25MB (documents) |

### Upload Validation

1. MIME type verification
2. Extension whitelist check
3. File size limit enforcement
4. Malware scanning (optional)
5. Filename sanitization

---

## API Security

### Rate Limiting

```php
// Default: 60 requests per minute
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// Login: 5 attempts per minute
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

### Authentication Flow

1. User submits credentials
2. Server validates and issues Sanctum token
3. Token stored in secure storage (not localStorage)
4. All API requests include `Authorization: Bearer {token}`
5. Token validated on each request

---

## Vulnerability Reporting

### How to Report

If you discover a security vulnerability, please report it responsibly:

1. **Email:** security@bundapaud.surabaya.go.id
2. **Subject:** [SECURITY] Brief description

### What to Include

- Detailed description of the vulnerability
- Steps to reproduce
- Potential impact assessment
- Suggested remediation (if any)

### Response Timeline

| Stage              | Timeline |
| ------------------ | -------- |
| Acknowledgment     | 24 hours |
| Initial Assessment | 72 hours |
| Remediation Plan   | 7 days   |
| Fix Deployment     | 14 days  |

---

## Security Checklist

### Development

- [ ] Use parameterized queries (Eloquent)
- [ ] Validate all user input
- [ ] Sanitize file uploads
- [ ] Implement proper error handling
- [ ] Review third-party dependencies

### Deployment

- [ ] Enable HTTPS (TLS 1.3)
- [ ] Configure security headers
- [ ] Set up rate limiting
- [ ] Enable database encryption
- [ ] Configure firewall rules

### Monitoring

- [ ] Log authentication attempts
- [ ] Monitor API usage patterns
- [ ] Set up alerting for anomalies
- [ ] Regular security audits

---

## Dependencies Security

### Backend (Laravel)

```bash
# Check for vulnerabilities
composer audit
```

### Frontend (Vue.js)

```bash
# Check for vulnerabilities
npm audit

# Fix vulnerabilities
npm audit fix
```

---

## Compliance

This project follows security best practices aligned with:

- **OWASP Top 10** - Web Application Security Risks
- **Indonesian Government** - BSSN Cybersecurity Guidelines
- **Data Protection** - UU ITE & UU PDP compliance

---

_Last Updated: January 2026_

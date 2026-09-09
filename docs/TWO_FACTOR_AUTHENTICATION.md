# Two-Factor Authentication (2FA / TOTP) & Trusted Devices (Planka v2)

> ⚠️ **Notice on Testing & Support:**
> Two-Factor Authentication (TOTP) and OIDC/SSO endpoints are implemented according to the Planka v2 OpenAPI specification, but have not been fully verified in automated live integration test suites.
> If you encounter any issues or bugs when using TOTP or OIDC authentication, please **open an Issue on GitHub** with a detailed description, error message, and reproduction steps.

Planka v2 supports Two-Factor Authentication (TOTP) via authenticator apps (e.g., Google Authenticator, 1Password) and Trusted Device management.

---

## 1. Logging In with 2FA / TOTP

When authenticating via username and password, Planka returns a challenge response if TOTP is enabled on the user account:

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    user: 'user@example.com',
    password: 'secure_password',
    baseUri: 'http://192.168.1.100',
    port: 3000
);

$planka = new PlankaClient($config);
$result = $planka->authenticate();

if (!$result->success) {
    if ($result->requiresTotp()) {
        // Complete login using 6-digit code from authenticator app
        $result = $planka->verifyTotp($result->pendingToken, '123456');
    }

    if ($result->requiresTerms()) {
        $terms = $planka->terms()->get();
        $result = $planka->acceptTerms($result->pendingToken, $terms->signature);
    }
}

// Authentication complete, JWT token stored automatically
$projects = $planka->project()->list();
```

---

## 2. Setting Up & Enabling TOTP for a User

```php
// Step 1: Initialize TOTP setup (returns secret and QR code URI)
$setup = $planka->user()->setupTotp(
    userId: '1357158568008091264',
    currentPassword: 'current_password'
);

echo "Secret: {$setup->secret}\n";
echo "Provisioning URI: {$setup->provisioningUri}\n";

// Step 2: Confirm and enable TOTP with verification code
$user = $planka->user()->enableTotp(
    userId: '1357158568008091264',
    currentPassword: 'current_password',
    code: '123456'
);
```

---

## 3. Disabling TOTP & Regenerating Recovery Codes

```php
// Regenerate backup recovery codes
$recoveryCodes = $planka->user()->regenerateTotpRecoveryCodes(
    userId: '1357158568008091264',
    currentPassword: 'current_password',
    code: '123456'
);

// Disable TOTP for user
$user = $planka->user()->disableTotp(
    userId: '1357158568008091264',
    currentPassword: 'current_password',
    code: '123456'
);
```

---

## 4. Managing Trusted Devices

```php
// List user's trusted devices/sessions
$devices = $planka->user()->listTrustedDevices(userId: '1357158568008091264');

foreach ($devices as $device) {
    echo "Device ID: {$device->id}, Name: {$device->name}, IP: {$device->ipAddress}\n";
}

// Revoke a trusted device
if (!empty($devices)) {
    $planka->user()->deleteTrustedDevice(
        userId: '1357158568008091264',
        deviceId: $devices[0]->id
    );
}
```

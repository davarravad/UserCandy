<?php
require_once __DIR__ . '/config.php';

/**
 * Verify reCAPTCHA token with Google API
 *
 * @param string $token User response token provided by the reCAPTCHA client-side integration.
 * @return bool True if token is valid, false otherwise.
 */
function verifyRecaptcha(string $token): bool
{
    if (empty($token)) {
        return false;
    }

    $response = file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode(RECAPTCHA_SECRET_KEY) . '&response=' . urlencode($token)
    );
    if ($response === false) {
        return false;
    }
    $result = json_decode($response, true);
    return isset($result['success']) && $result['success'] === true;
}

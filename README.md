# UserCandy

UserCandy Framework

This example includes basic login and registration pages that use Google reCAPTCHA
when the provider is not `windows`, `google`, or `discord`.

## Setup

1. Copy `config.php` and replace `RECAPTCHA_SITE_KEY` and `RECAPTCHA_SECRET_KEY`
   with your credentials from the Google reCAPTCHA admin console.
2. Access `login.php` or `register.php`. Append `?provider=local` to enforce
   reCAPTCHA or `?provider=google` to skip it.

These pages are placeholders and should be extended with actual authentication
logic.

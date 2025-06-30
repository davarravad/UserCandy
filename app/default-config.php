<?php
// Default configuration template.
// Rename this file to config.php and customize your settings.

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'usercandy',
        'user' => 'root',
        'pass' => '',
    ],
    'base_url' => '/',
    'enable_google_login' => false,
    'google_client_id' => '',
    'google_client_secret' => '',
    'enable_discord_login' => false,
    'discord_client_id' => '',
    'discord_client_secret' => '',
    'enable_windows_login' => false,
    'windows_client_id' => '',
    'windows_client_secret' => '',
    'enable_facebook_login' => false,
    'facebook_client_id' => '',
    'facebook_client_secret' => '',
    // Enable Google reCAPTCHA on login and register forms
    'enable_recaptcha' => false,
    'recaptcha_site_key' => '',
    'recaptcha_secret_key' => '',

    // Language settings
    'language' => 'en', // default language code
    // Available translations included in languages/
    'available_languages' => [
        'en','de','zh','hi','es','fr','ar','bn','ru','pt',
        'id','ur','ja','sw','mr','te','tr','ta','vi','ko'
    ],

    // Default template directory inside templates/
    'template' => 'default',

    // Navigation links shown in the header
    'nav_links' => [
        ['title' => 'home', 'url' => ''],
        ['title' => 'about', 'url' => 'about'],
        ['title' => 'contact', 'url' => 'contact'],
    ],
];

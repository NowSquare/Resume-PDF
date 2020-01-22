<?php

  /*
   |--------------------------------------------------------------------------
   | Defaults
   |--------------------------------------------------------------------------
   |
   | The values below are defaults for the app.
   | Can be overridden in the .env file.
   |
   */

return [
  /*
   |--------------------------------------------------------------------------
   | App
   |--------------------------------------------------------------------------
   */

  'app_name' => env('APP_NAME', 'My Business'),
  'app_logo' => env('APP_LOGO', ''),
  'app_url' => env('APP_URL', 'https://localhost'),
  'app_contact_email' => env('APP_CONTACT_EMAIL', 'hi@example.com'),
  
  /*
   |--------------------------------------------------------------------------
   | E-mail
   |--------------------------------------------------------------------------
   */

  'mail_from_name' => env('MAIL_FROM_NAME', 'My Business'),
  'mail_from_address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),

  /*
   |--------------------------------------------------------------------------
   | Localization
   |--------------------------------------------------------------------------
   */

  'language' => env('DEFAULT_LANGUAGE', 'en'),
  'locale' => env('DEFAULT_LOCALE', 'en_US'),
  'timezone' => env('DEFAULT_TIMEZONE', 'UTC'),
  'currency' => env('DEFAULT_CURRENCY', 'USD'),

  /*
   |--------------------------------------------------------------------------
   | Social
   |--------------------------------------------------------------------------
   */

   'social' => [
    ['name' => 'Facebook', 'icon' => 'mdi-facebook', 'url' => env('SOCIAL_FACEBOOK', '')],
    ['name' => 'Instagram', 'icon' => 'mdi-instagram', 'url' => env('SOCIAL_INSTAGRAM', '')],
    ['name' => 'Twitter', 'icon' => 'mdi-twitter', 'url' => env('SOCIAL_TWITTER', '')],
    ['name' => 'Youtube', 'icon' => 'mdi-youtube', 'url' => env('SOCIAL_YOUTUBE', '')],
    ['name' => 'Medium', 'icon' => 'mdi-medium', 'url' => env('SOCIAL_MEDIUM', '')],
    ['name' => 'Snapchat', 'icon' => 'mdi-snapchat', 'url' => env('SOCIAL_SNAPCHAT', '')],
    ['name' => 'LinkedIn', 'icon' => 'mdi-linkedin', 'url' => env('SOCIAL_LINKEDIN', '')],
    ['name' => 'Github', 'icon' => 'mdi-github-circle', 'url' => env('SOCIAL_GITHUB', '')]
   ],
   
   /*
    |--------------------------------------------------------------------------
    | Limitations
    |--------------------------------------------------------------------------
    */

    'max_items' => env('MAX_ITEMS', 25),
    'max_items_premium' => env('MAX_ITEMS_PREMIUM', 50),
   
   /*
    |--------------------------------------------------------------------------
    | Third party analytics
    |--------------------------------------------------------------------------
    */
 
   'google_analytics' => env('CONFIG_GOOGLE_ANALYTICS', ''),
];

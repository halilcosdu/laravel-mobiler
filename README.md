[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/halilcosdu/laravel-mobiler/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/halilcosdu/laravel-mobiler/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/halilcosdu/laravel-mobiler/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/halilcosdu/laravel-mobiler/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
## Laravel Mobiler

Laravel Mobiler is a ready-to-use panel template for mobile devices, developed using Laravel and FilamentPHP, that handles authentication processes.

FilamentPHP : https://filamentphp.com/docs

## Features
- **User Authentication**: Laravel Mobiler provides a complete user authentication system, including registration, login, and password reset functionality.
- **Mobile-First Design**: The panel template is designed with a mobile-first approach, ensuring a seamless user experience on mobile devices.
- **Responsive Layout**: The template is fully responsive and adapts to different screen sizes, making it suitable for a wide range of devices.
- **Customizable Theme**: Laravel Mobiler allows you to customize the theme colors, fonts, and other design elements to match your brand identity.
- **User Management**: The template includes user management features, such as user roles, permissions, and profile settings.
- **Dashboard**: Laravel Mobiler comes with a dashboard that provides an overview of key metrics and data visualizations.
- **Localization Support**: The template supports multiple languages and allows you to easily switch between different language options.
- **Dark Mode**: Laravel Mobiler offers a dark mode option for users who prefer a darker color scheme.
- **Built-in Components**: The template includes a variety of built-in components, such as buttons, forms, tables, and modals, to help you build your application faster.
- **Easy Integration**: Laravel Mobiler is built on Laravel and FilamentPHP, making it easy to integrate with existing Laravel applications.

<a href="https://i.ibb.co/cc6f183/Screenshot-2024-04-28-at-15-59-27.png">
<img src="https://i.ibb.co/cc6f183/Screenshot-2024-04-28-at-15-59-27.png" alt="Screenshot" style="width:100%;">
</a>

<a href="https://i.ibb.co/JvBDCbm/Screenshot-2024-04-30-at-17-37-12.png">
<img src="https://i.ibb.co/JvBDCbm/Screenshot-2024-04-30-at-17-37-12.png" alt="Screenshot" style="width:100%;">
</a>

<a href="https://i.ibb.co/5n8YY3k/Screenshot-2024-04-30-at-17-38-51.png">
<img src="https://i.ibb.co/5n8YY3k/Screenshot-2024-04-30-at-17-38-51.png" alt="Screenshot" style="width:100%;">
</a>


## Installation

```bash
composer install

php artisan migrate

php artisan make:filament-user

php artisan icons:cache

php artisan filament:cache-components
```
#### Example request for token creation: {{host}}/api/token
```php
Route::post('/token', TokenController::class)->name('token.store');
```
###### You may collect this data from mobile devices and send it to the server.
```json
{
    "timezone": "America/New_York",
    "os_type": "ios",
    "os_version": "14",
    "device_name": "My Phone",
    "device_type": "14.2.3",
    "app_version": "100",
    "client_device_code": "03c882bd-efaa-3c49-b6cb-987cdce434cf",
    "language_code": "EN",
    "country_code": "US"
}
```

#### Revenuecat Integration
###### Revenuecat webhook callback url.

```php
Route::post('/subscriptions', [SubscriptionController::class, 'webhook'])->name('subscriptions.webhook');
```
## WIP

```bash
    - Firebase Integration
    - Push Notification
    - API Documentation
    - Readme.md Update
```
## Current routes

```bash
Route::post('/token', TokenController::class)->name('token.store');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', ProfileController::class)->name('profile.show');
    Route::post('/tickets', TicketController::class)->name('tickets.store');
});

Route::post('/subscriptions', [SubscriptionController::class, 'webhook'])->name('subscriptions.webhook');
```

# OpenID Connect (OIDC) Provider for Laravel Socialite

![Laravel Support: v9, v10, v11](https://img.shields.io/badge/Laravel%20Support-v9%2C%20v10%2C%20v11-blue) ![PHP Support: 8.1, 8.2, 8.3](https://img.shields.io/badge/PHP%20Support-8.1%2C%208.2%2C%208.3-blue)

## Installation & Basic Usage

```bash
composer require taufik-t/uauth-oidc-client
```

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'uauth' => [
    'base_url' => env('UAUTH_BASE_URL'),
    'client_id' => env('UAUTH_CLIENT_ID'),
    'client_secret' => env('UAUTH_CLIENT_SECRET'),
    'redirect' => env('UAUTH_REDIRECT_URI'),
],
```

The base URL must be set to the URL of your OIDC endpoint excluding the `.well-known/openid-configuration` part. For example:
If `https://auth.company.com/.well-known/openid-configuration` is your OIDC configuration URL, then `https://auth.company.com` must be your base URL.

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

#### Laravel 11+

In Laravel 11, the default `EventServiceProvider` provider was removed. Instead, add the listener using the `listen` method on the `Event` facade, in your `AppServiceProvider` `boot` method.

```php
Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
    $event->extendSocialite('uauth', \SocialiteProviders\UAuth\Provider::class);
});
```

#### Laravel 10 or below

Add the event to your listen[] array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \SocialiteProviders\UAuth\UAuthExtendSocialite::class.'@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade
installed):

```php
return Socialite::driver('uauth')->redirect();
```

### Returned User fields

- `id`
- `name`
- `email`

More fields are available under the `user` subkey:

```php
$user = Socialite::driver('uauth')->user();

$locale = $user->user['locale'];
$email_verified = $user->user['email_verified'];
```

### Customizing the scopes

You may extend the default scopes (`openid email profile`) by adding a `scopes` option to your OIDC service configuration and separate multiple scopes with a space:

```php
'uauth' => [
    'base_url' => env('UAUTH_BASE_URL'),
    'client_id' => env('UAUTH_CLIENT_ID'),
    'client_secret' => env('UAUTH_CLIENT_SECRET'),
    'redirect' => env('UAUTH_REDIRECT_URI'),

    'scopes' => 'groups roles',
    // or
    'scopes' => env('UAUTH_SCOPES'),
],
```

---

Based on the work of [jp-gauthier](https://github.com/jp-gauthier)

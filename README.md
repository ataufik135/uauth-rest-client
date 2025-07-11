# UAuth REST API Client for Laravel

![Laravel Support: v9, v10, v11](https://img.shields.io/badge/Laravel%20Support-v9%2C%20v10%2C%20v11-blue) ![PHP Support: 8.1, 8.2, 8.3](https://img.shields.io/badge/PHP%20Support-8.1%2C%208.2%2C%208.3-blue)

## Installation & Basic Usage

```bash
composer require taufik-t/uauth-rest-client
```

### Add configuration to `config/uauth.php`

```php
'api' => [
    'base_url' => env('UAUTH_API_BASE_URL', null),
    'ssl_verify' => env('UAUTH_API_SSL_VERIFY', true),
    'timeout' => env('UAUTH_API_TIMEOUT', 30),
    'connect_timeout' => env('UAUTH_API_CONNECT_TIMEOUT', 10),
    'provider_id' => env('UAUTH_API_PROVIDER_ID', null),
  ],
```

#### `.env`

```ini
UAUTH_API_BASE_URL="https://auth.application.com/api/v1"
UAUTH_API_PROVIDER_ID="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"

// optional
UAUTH_API_SSL_VERIFY=true // ubah `false` untuk development atau local
UAUTH_API_TIMEOUT=30
UAUTH_API_CONNECT_TIMEOUT=10
```

### Usage

Tambahkan middleware `sso.api` untuk memproteksi resource api:

#### `routes/api.php`

```php
Route::group(['middleware' => ['sso.api']], function () {
    // tempatkan route resource yang ingin di proteksi disini
});
```

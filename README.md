
<p align="center"><img src="/art/socialcard.png" alt="Laravel Lock Screen by Sertxu Developer"></p>

# Add a lock screen to your Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sertxudeveloper/laravel-lockscreen.svg)](https://packagist.org/packages/sertxudeveloper/laravel-lockscreen)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sertxudeveloper/laravel-lockscreen/run-tests?label=tests)](https://github.com/sertxudeveloper/laravel-lockscreen/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Codecov Test coverage](https://img.shields.io/codecov/c/github/sertxudeveloper/laravel-lockscreen)](https://app.codecov.io/gh/sertxudeveloper/laravel-lockscreen)
[![Total Downloads](https://img.shields.io/packagist/dt/sertxudeveloper/laravel-lockscreen.svg)](https://packagist.org/packages/sertxudeveloper/laravel-lockscreen)

This packages adds the functionality to add a lock screen in your app.

The users will be required to re-enter the password once the session has timed out due to inactivity.

## Installation

You can install the package via composer:

```bash
composer require sertxudeveloper/laravel-lockscreen
```

Next, you should run the installation command:

```bash
php artisan lockscreen:install
```

## Usage

Once you have installed the package, you're users will be redirected to the `locked` route once the session has expired.

In the configuration file you can specify how much time the user can be inactive before locking it's account.

## Testing

This package contains tests, you can run them using the following command:

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/sertxudeveloper/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sergio Peris](https://github.com/sertxudev)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

<br><br>
<p align="center">Copyright © 2022 Sertxu Developer</p>

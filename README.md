# My Freelance Tool

The idea is to have a tool that can help me to manage my freelance work. I want to be able to track my time, my expenses, my invoices, and my clients. I want to be able to generate reports and to have a dashboard with the most important information.

## Features
- Register clients
- Register banks
- Register other payment methods
- Handle invoices

## Technologies
- PHP
- MySQL
- Laravel
- Filament PHP

## Installation
1. Clone the repository
2. Run `composer install`
3. Run `php artisan migrate`
4. Run `php artisan db:seed`
5. Run `php artisan serve`
6. Open your browser and go to `http://localhost:8000`
7. Enjoy!

By default, the application will create a user with the following credentials:
- Email: `admin@mail.test`
- Password: `password`

## Usage
You can use the application to manage your freelance work. You can register your clients, your banks, and your payment methods. You can also handle your invoices.

The app is in spanish, but you can change the language in the `config/app.php` file.

```php
'locale' => 'es',
```

## License
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Authors
- [Jerson Moreno](https://jersonmr.netlify.app)

## Acknowledgments
- [Filament PHP](https://filamentphp.com)
- [Laravel](https://laravel.com)
- [PHP](https://www.php.net)
- [MySQL](https://www.mysql.com)
- [Tailwindcss](https://tailwindcss.com)
- [Alpinejs](https://alpinejs.dev)
- [Livewire](https://livewire.laravel.com)

## Roadmap
- [ ] Add lang picker
- [ ] Add expenses
- [ ] Add time tracking
- [ ] Add reports
- [ ] Add dashboard
- [ ] Add notifications
- [ ] Add user roles
- [ ] Add user permissions
- [ ] Add user settings
- [ ] Customize default theme

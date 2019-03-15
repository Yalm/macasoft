# MACASOFT KNOWLEDGE TEST

Development of a user module, where you can list, create, edit and
remove

## Installation

1. Clone the repo and `cd` into it
1. `composer install`
1. Rename or copy `.env.example` file to `.env`
1. `php artisan key:generate`
1. `php artisan jswt:secret`
1. Set your database credentials in your `.env` file
1. `php artisan migrate --seed`
1. `php artisan serve` or use Laravel Valet or Laravel Homestead
1. Visit `localhost:8000` in your browser

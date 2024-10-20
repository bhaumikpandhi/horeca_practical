## Installation

Please follow below steps to setup project.

- Clone the repository in the local `git clone https://github.com/bhaumikpandhi/horeca_practical.git`
- run `cd horeca_practical`
- copy .env.example to .env `cp .env.example .env`
- run `composer install`
- run `php artisan key:generate`
- Change DB details in .env file
- run `php artisan migrate --seed` command to create required DB tables and records
- run `php artisan passport:install`
- run `php artisan passport:client --personal`
- Copy above command values into `PASSPORT` env variables 
  - PASSPORT_PERSONAL_ACCESS_CLIENT_ID="1"
  - PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET="xxx"
- run `php artisan test` to execute test commands/files 
  - As of now I have not used separate DB for testing, but we can do that.


## API Details

- Import `postman_collection.json` file into postman.
- Change `api_url` variable to your project `api` URL
  - in my local it is `http://horecastore.test/api/v1`



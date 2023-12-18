# How To Run

To run the project follow these steps. I have exported the postman API endpoints as JSON. import it in postman.

## How to run without docker

Firstly, if you want to run without docker to get understanding of independent behaviour.

Open a terminal

1. cd server/gateway/
2. cp .env.example .env
3. composer install
4. php artisan key:generate
5. php artisan jwt:secret
6. php artisan make:database ip_handler
7. php artisan migrate
8. cp .env ../authentication/.env
9. cp .env ../ip-handler/.env
10. php -S localhost:8000 -t public

<!-- Move to "authentication" microservice -->
Open another teminal

1. cd server/authentication
2. composer install
3. php artisan migrate
4. php artisan db:seed --class=UsersTableSeeder
5. php -S localhost:8001 -t public

<!-- Move to "ip-handler" microservice -->
Open another teminal

1. cd server/ip-handler
2. composer install
3. php artisan migrate
4. php artisan db:seed --class=AuditTrailSeeder
5. php artisan db:seed --class=IpAddressSeeder
6. php -S localhost:8002 -t public

Now we should have users in db with `'email'= 'admin.user@example.com'` and `'password' = 'secret_password'`

## To run front end
<!-- Move to "angular" -->
1. cd client
2. npm install
3. ng serve --open

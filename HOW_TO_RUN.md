# How To Run

To run the project follow these steps-

## To run server

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
11. cd authentication
12. composer install
13. php artisan migrate
14. php artisan db:seed --class=UsersTableSeeder
15. php -S localhost:8001 -t public

<!-- Move to "ip-handler" microservice -->
16. cd ip-handler
17. composer install
18. php artisan migrate
19. php artisan db:seed --class=AuditTrailSeeder
20. php artisan db:seed --class=IpAddressSeeder
21. php -S localhost:8002 -t public

Now we should have users in db with `'email'= 'admin.user@example.com'` and `'password' = 'secret_password'`

<!-- Move to "angular" -->
22. cd client
23. npm install
24. ng serve --open
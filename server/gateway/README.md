# Gateway Microservice

All our requests will come to this microservice and it will redirect them to appropiate microservice. We can implement rate limiting here.

## Journey

<!-- Create DB -->
First we create a database. I have already created a custom command manually (since lumen don't have easy way to create command) for creating database. For simplicity, the command skips advanced approaches preffered in production environment.

In below section, use the dbname you added in `.env`.

```bash
php artisan make:database your_db_name
```

<!-- Run Migration -->
```bash
php artisan migrate
```

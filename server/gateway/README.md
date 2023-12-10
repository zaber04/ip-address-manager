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

## Rate Limit

For rate limit, we implemented token bucket algorithm. We will allow a certain number of requests (env file will give us value) to be added to bucket at a fixed rate. When a request comes, we remove a token from the bucket. If the bucket is empty, the request is denied. This approach allows some burstiness while still limiting the rate. We are using this approach assuming, this gateway isn't distrubuted, rather centralized.

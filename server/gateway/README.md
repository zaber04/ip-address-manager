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

We are using ip-based-limiter instead of key-based-limiter since we don't have authenticated user initially. We can still enforce key-based-limit in applicable microservice seperately.

For this project context, we are implimenting rate limit for this microservice only. If we needed to implement in multiple microservices - deploying it as a package and importing in each services (as needed) would be more pragmatic.

## Why not queue the request and process later?

We chose rate-limiter over request-throttler to prevent abuse. However, it can still be implemented later if needed.

## Why didn't we use third party rate limiter?

To keep our microservice as light as possible, we are focused on using minimal & as-required packages. Instead of bulky & advanced packagaes we are using simple as-needed implementation to reduce lag.

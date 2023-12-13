# authentication microservice

We are implementing our identity provider microservice here.

## Tokens

This microservice provides access_token and refresh_token

## Access control

We did not implement Host Based Acces Control (HBAC), Role Based Acces Control (RBAC) and Time Based Acces Control (TBAC).

## How to generate JWT secret?

Run the command below

```bash
php artisan jwt:secret
```

Copy the same jwt to other `.env` files as well

```bash
cp .env ../gateway/.env
cp .env ../ip-handler/.env
```

# How To Run

To run the project follow these steps. I have exported the postman API endpoints as JSON. import it in postman.

## Run with Docker

To run the project through docker run the following command

1. Copy project environment file from `.env.example` to `.env`

    ```bash
    cp server/gateway/.env.example server/gateway/.env
    cp server/authentication/.env.example server/authentication/.env
    cp server/ip-handler/.env.example server/ip-handler/.env
    ```

2. Init docker

    ```bash
    docker-compose up --build
    ```

3. Check `phpmyadmin`
    Visit <http://localhost:3305> and login to phpmyadmin.
    > username: root
    > password: "" (keep empty)

4. Let's access the docker-container. We will create a database. In production environment, we can auto generate this part using a `/docker-entrypoint-initdb.d` file and then auto generate db using `.sql` file and also **grant** and **flush** priviledges for users. For simplicity, we skipped this since we only have 1 db.

I have created a **custom create database** command for Lumen (since it doesn't have this) and will use this command. We can create manually from phpmyadmin (<http://localhost:3305>) as well.

So, now acces docker. First we will enter our `gateway` microservice.

   ```bash
   docker exec -it ip-address-manager-gateway /bin/bash
   php artisan make:database ip_handler
   php artisan migrate
   php artisan db:seed # optional
   # mysql -u root -p -h ip-address-manager-mysql # to access mysql cli
   ```

Check in phpmyadmin (<http://localhost:3305>) if `ip_handler` database is created.

Now we will access `authentication` microservice.

   ```bash
   docker exec -it ip-address-manager-authentication /bin/bash
   php artisan migrate
   php artisan db:seed # required
   ```

We now have 10 dummy users and a custom cuser with following credentials

> Email: <admin.user@ip-manager.com>
> Pass: secret_password

Now we will access `ip-handler` microservice.

   ```bash
   docker exec -it ip-address-manager-ip-handler /bin/bash
   php artisan migrate
   php artisan db:seed # required
   ```

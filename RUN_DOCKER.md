# How To Run

To run the project follow these steps. I have exported the postman API endpoints as JSON. import it in postman.

## Run with Docker

To run the project through docker run the following command

1. Copy environment files

    ```bash
    cp server/gateway/.env.example server/gateway/.env
    cp server/authentication/.env.example authentication/gateway/.env
    cp server/ip-handler/.env.example server/ip-handler/.env
    ```

2. Init docker

    ```bash
    docker-compose up --build
    ```

3. Check `phpmyadmin` (optional)
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
    # php artisan route:list # to see the routes
    # mysql -u root -p -h ip-address-manager-mysql # to access mysql cli
    ```

    You can now verify in phpmyadmin (<http://localhost:3305>) (user: root and empty password) if `ip_handler` database is created.

    Now we will access `authentication` microservice. You will have to exit from the previous docker bash using `CTRL+Z` or something similar or open another terminal.

    ```bash
    docker exec -it ip-address-manager-authentication /bin/bash
    php artisan migrate
    php artisan db:seed # required
    # php artisan route:list # to see the routes
    ```

    We now have 10 dummy users and a custom cuser with following credentials.

    > Email: <admin.user@ip-manager.com>
    > Pass: secret_password

    Now we will access `ip-handler` microservice.

    ```bash
    docker exec -it ip-address-manager-ip-handler /bin/bash
    php artisan migrate
    php artisan db:seed # required
    # php artisan route:list # to see the routes
    ```

    Now, our APIs are ready. We can now check via postman.
    Kindly import `IpAddressManager.postman_collection.json` file into postman.
    It uses `{{host_name}}` variable which is equal to `http://localhost:8000`

    Here's the other postman environment variables
    "host_name" : "<http://localhost:8000>",
    "ip_handler_host" : "<http://127.0.0.1:8002>",
    "authentication_host" : "<http://127.0.0.1:8001>"

    Initially we will hit `login` endpoint to get `accessToken` which we will set to `{{JWT_TOKEN}}` in postman environment and then test other endpoints. For development purpose, using a `masterToken` is a typical choice, however, in this project we didn't add that support.

5. I have updated my host file and added three entries
    127.0.0.1   authentication.localhost
    127.0.0.1   ip-handler.localhost
    127.0.0.1   gateway.localhost

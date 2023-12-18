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

    Add a `-d` for headless mode at the end of the bash command. I simply prefer looking at docker messages.

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

   After this, when I tried to access the docker APIs through POSTMAN, got a huge shock. Apache is blocking all my requests! After some hours of google search with a migrain pain, I was able to setup and improve apache, reverse proxy, htaccess with improved docker setup. It was an inredible journey. And then docker was causing in-container-request forward blocking issue as well. Finally all the APIs work in docker.

   Please test in postman after importing the json file.

6. After testing & verifying the API responses, let's visit frontend. Visit <http://localhost:4200> for our angular front end. I haven't been doing much front end work recently and mostly out of touch with recent best practices for angular. Created a simple ui with help from chat gpt. So, the quality is nothing to be happy about.

> You might need to wait like a minute to use the angular ui after docker builds.

Let's login using the credentials
    > Email: <admin.user@ip-manager.com>
    > Pass: secret_password

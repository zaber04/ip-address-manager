# ip-address-manager

We are building an IP Address Management Solution using microservice architecture.
This is my first time working with Lumen and I'm excited about it.

## Goal

Here, in our app, users will be able to log in and store (in DB) new IP addresses with a label.
<!-- Upon login user will have `access_token` and `refresh_token`. -->
During storing in db, IP addresses will be validated. Authenticaed & authorized user can modify the label of an IP address.
We will maintain history (audit trail) for every login, addition or change.
Users can view an audit log of changes made.

## Communication

We are using `json` as ommunication protocol due to size of the app. However, if there is lots of intercommunicating services, we will use `gRPC` to reduce transmission latency.


## Authentication Microservice

1. Responsible for user authentication and token management.
2. Handles user login, issues authentication tokens, and supports token refresh.
3. Ensures that all subsequent requests to other microservices require a valid authenticated token.
4. Manages user sessions and token expiration.

## Gateway Microservice

1. Receives all incoming requests
2. Rate limits requests

## IpHandler Microservice

1. Stores IP
2. Updates IP
3. Shows IP list
4. Maintains IP history
5. Maintains users actions log as "audit log" for each session 

We didn't use a seperate "Audit Log Microservice" due to small number of functionality and direct depency on "ip update".


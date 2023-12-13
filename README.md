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

## Monorepo and Database

Our microservices are in monorepo setup allowing easier resource sharing. We are using same database setup for all the miroservices requiring them to use less requests and local fetching.

## authentication

We will use JWT for stateless authentication. Each microservice will be careful to avoid algo none attack. This choice comes with the requirement of implementing a centralized blacklist (SPOF) or kafka based decentralized event subscription based blacklist. However, for simplicity, we didn't implement a authorization revokation mechanism.

## Authorization

We are assuming, all logged in users have access to all routes and hence didn't implement acess control policy.

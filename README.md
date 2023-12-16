# ip-address-manager

I am building an IP Address Management Solution using microservice architecture.
I will be using API-GATEWAY-PATTERN for developing our microservices. This is one of the most common and simplest design pattern for building microservices among 10 patterns for microservices.

## How To Run

To run the project, please follow the `HOW_TO_RUN.md` file instead. This file primarily consists of the experience during the project journey.

## Research

I sepnt the first one and half days researching on techs on the pros on cons and eventually decided to use Lumen Micro Framework for the project.

## Declaration

This is my first time working with Lumen Micro Framework and I'm excited about it. This is also my first time implementing microservices in PHP environment. Previously I have implemented microservices in node based environment. Therefore, there might be some beginner mistakes. So, I appreciate feedback and suggestion to improve the project. I am also working within time constraints and hence UML, ERD diagrams weren't created yet.

## Microservices

## Authentication Microservices

1. Responsible for user authentication and token management.
2. Handles user login, issues authentication tokens, and supports token refresh.
3. Ensures that all subsequent requests to other microservices require a valid authenticated token.
4. Manages user sessions and token expiration.

## Gateway Microservice

1. Receives all incoming requests and forwards them
2. Rate limits requests

*** We intentionally didn't attach any header to verify whether the request is from `gateway` microservice or not. It simply helps us in postman testing. In production environment, apart from jwt, we will be using another level of authorization to enforce routing flow.

## IpHandler Microservice

1. Stores IP
2. Updates IP
3. Shows IP list
4. Maintains IP history
5. Maintains users actions log as "audit log" for each session (log in, changes, log out)

I didn't use a seperate "Audit Log Microservice" due to small number of functionality and direct depency on "ip update". However, in large scale scenario, a seperate microservice with message queue is preferable for high throughput and fault taulerance.

## Routing

For combined routing I have come up with two solutions -

1. `Dynamic Loading with strict host`: Since I didn't use a `service registry microservice` due to the application scope. I implemented a simple `RouterService` instead. It loads all services dynamically.

2. `Dynamic Loading with flexible host`: Later I implemented a slightly different approach to allow flexible host naming using env. This approach felt more cleaner and less coupled.

## Routing Challenges

Not having any `fallback` or wildcard based `any` in lumen unlike laravel poses a strong challenge to handle `route not found` and `route redirection`. I have come up with 3 approaches so far but neither feels clean enough. Edited the default `Exception/Handler.php` for `route not found`.

## Goal

Here, in our app, users will be able to log in and store (in DB) new IP addresses with a label.
Upon login user will have `access_token` which will be refreshed.
During storing in db, IP addresses will be validated. Authenticated & authorized user can modify the label of an IP address.

The app will maintain history (audit trail) for every login, addition or change.
Users can view an audit log of changes made.

## Communication

I am  using `json` as ommunication protocol due to size of the app. However, if there is lots of intercommunicating services, `gRPC` is preferred to reduce transmission latency. Specially in PHP, where json isn't native, `gRPC` is likely to provide `more than 40%` latency improvement.

## Limitations & Challenges

Since Lumen is a micro framework, a lot of commands aren't available and doesn't offer auto generation. Many features and libraries and facades of laravel are absent in lumen. Hence most of the codes require manual writing, adding time in development. However, it's stripped down structure offers the flexibility to add only what's needed for each service.

I have implimented several custom commands (which are similar to laravel) to make the journey smoother. For at least one of the commands none of the stackoverflow solution worked due to dependecy changes and I had to come up with a scratch up solution. It was exhilarating. I will publish this as a public package in github.

In some cases, removal of codes in Lumen has adverse effect and doesn't even allow basic functionalities such as requestFactory() and forces the developers to copy-paste the code from laravel framework code. Lumen also has much weaker and outdated documentation

## Comments / Doxygen

Lumen doesn't have much option for auto generate docblocks and comments. Hence most of the comments and docblocks are genrated manually and with a little help from a doxygen extension in IDE.

## Monorepo and Database

Our microservices are in monorepo setup allowing easier resource sharing. I am using same database setup for all the miroservices requiring them to use less requests and local fetching.

Typically microservices are built in polyrepo which provides better seperation and makes development easier. However, this also means fetching resources require composer library or hitting an API endpoint which can add up delay.

Monorepo design solves the delay issue by sharing libraries. It's only challege is maintaining strict library namespace structure for library sharing. It also don't require making external API request.

Maintaining Database operations in monorepo requires careful implementation of the models and factories.

Only brilliant outcome was, I was able to create custom commands in one microservice and reference them in other microservices kernel.

## Monorepo Branch Approach

For monorepo, I have come up with my own branching and naming strategy. I don't know what most other people does, but I have come up with my own solution and found it be easier to maintain.

Here it is -
For each microservice, a seperate sub branch under dev branch (`dev-{{micro_service_name}}`) is created. Ideally, changes for each microservice is made within the respective branch and then merged with dev (both way). Additionally for specific sub-project or sub-service, more subbranch can be creatd following *STRICT* naming convention. A sub branch will have their parent branch as prefix, making it feel like a directory. For example, we have created `dev-gateway` for `gateway` microservice and we can create `dev-gateway-security` to explore additional API security practices (OWASP standard). In this convention, it is important to *NOT MERGE ANY BRANCH WITH ANOTHER AS YOU LIKE*.  `dev-gateway-security` should be merged with `dev-gateway` *ONLY*. `dev-gateway` can be merged with `dev` and with any branch with `dev-gateway-*` naming pattern. This maintains clean branch and any rollback or rebase or cherry picking becomes very easy.

It is also essential to commit files related to same microservices should be committed together (if in dev). Maintaining goruping as much as possible helps.

## Authentication

I will be using JWT for stateless authentication. Each microservice will be careful to avoid algo none attack. This choice comes with the requirement of implementing a centralized blacklist (SPOF) or kafka based decentralized event subscription based blacklist. However, for simplicity, I didn't implement a authorization revokation mechanism.

## Authorization

I am assuming, all logged in users have access to all routes and hence didn't implement acess control policy. Hence I didn't implement access control policy (role based, host based, time based, location based) or any SOD matrix.

## Subscription

In a monolith we can perform multiple actions together. However, here those actions might be in seperate microservice. Hence, we have to come up with approach for this.
So, we have a few solutions at hand -

1. `Message queue`: Using Kafka would provide us fault tolerance and at-least-once delivery guarantee.
2. `Redis cache pub-sub`: We can simply spun up a redis microservice and all microservice could use it as a pub-sub provider.
3. `API request`: We can actually create a few extra API endpoints to provide specific support. But it would simply create too many endpoints.
4. `Event Trigger`: We can trigger events from one microservice and set listeners on other microservices and take action on that.

Considering the number of events being small, I chose not to use a meesage queue or redis and used `event trigger` instead.

## Service Registry

Service registry allows auto integration of new microservice and endpoints. However, this app has only a handful of end points in our application and hence a service registry isn't implemented.

## CORS & Trusted Host & Trusted Proxies

Lumen doesn't come with these features and hence I have implemented a basic version of these for our application.

## Rate Limiter

Lumen doesn't come with rate limit or throttle middleware and hence I have implemented a basic version of these for our application.

## Api Response Pattern

I have implemented custom api response pattern for uniform behavior.

## Logging

I have implemented custom logging trait to make use of systematic logging for errors.

## Setbacks

In the middle of the project, my laptop crashed and some work was lost. Most of the day was lost trying to recover the device and res-setup everything in another older device with almost no dev setup.

## Code Duplications

In some cases, code duplications was used intentionally instead of using `shared library` or `git submodules` or `composer packages` or `symbolic links`. This is often decision choice by case. One example is defining `auth` as a middleware and binding to a class and registering in service provider has been done in each services. In this case, it saved some time. In Lumen, there isn't a much better approach anyway. Even with a library, the manual part is mandatory. You have to add stuff in `bootstrap/app.php` manually.

## Cross server communication Failure

1. One very amusing problem I faced is hitting an API endpoint of a service from POSTMAN works fine but fails when another microservice calls upon it. After following long trail of stack trace errors, it turns out in Lumen, you need to have a few more packages for inter communication.

2. If a request is forwarded to another service, it's essential to capture the status code of the forwarded service and integrate properly with originating service. Otherwise, first service will always return 200.

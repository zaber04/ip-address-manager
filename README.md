# ip-address-manager

We are building an IP Address Management Solution using microservice architecture.
This is my first time working with Lumen and I'm excited about it.

## Goal

Here, in our app, users will be able to log in and store (in DB) new IP addresses with a label.
<!-- Upon login user will have `access_token` and `refresh_token`. -->
During storing in db, IP addresses will be validated. Authenticaed & authorized user can modify the label of an IP address.
We will maintain history (audit trail) for every login, addition or change.
Users can view an audit log of changes made.

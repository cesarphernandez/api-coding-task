# API Backend Coding Task

This is the technical test project for API oriented backends.

# Final Development
This technical test was developed taking into account and complying with

- [x] Authentication
- [x] Authorization
- [x] Cache
- [x] Documentation

## Authentication
The authentication was implemented using JWT, the user must authenticate to obtain a token that will be used in the subsequent requests.

## Authorization
The authorization was implemented using the roles of the users, the roles are: `admin` and `user`, the `admin` role has access to all the endpoints, while the `user` role only has access to the `GET` endpoints.

## Cache
The cache was implemented using Redis, the cache is used to store the results of the `GET` requests, the cache has a time of 2 minutes.

## Documentation
The documentation was implemented using Swagger, the documentation can be accessed at the following URL: `http://localhost:8080/docs`
```bash
make build-documentation
```
This command generates the documentation using Swagger.

## Tests
The tests were implemented using PHPUnit, the tests cover the main functionalities of the application.
```bash
make tests
```
This command executes the tests.

## Extra Info
- The App was development according to Hexagonal Architecture, the App has the following structure
- The App uses Third Party libraries to Cache, JWT, Http and Swagger
- All Request has Validation using Validator and DTO classes
- The App has a Middleware to validate the JWT token
- The App has a Middleware to validate the Role of the User
- The App has a Middleware to Cache the GET requests
- The App and All of endpoints are documented using Swagger and Catch different types of errors
- This repo has a GitHub Action to run the tests and code quality checks
- This repo has a pre-commit hook to run the tests


## Build

```bash
make build
```

This command executes the Docker image building process and performs the [Composer](https://getcomposer.org) dependencies installation.

---

Type `make help` for more tasks present in `Makefile`.

## Functional requirements

**Implement a CRUD (Create-Read-Update-Delete) API.**

The following add-ons will be positively evaluated:

- Authentication
- Authorization
- Cache
- Documentation

---

A light infrastructure is provided with a populated MySQL database with example data and a web server using PHP built-in development server.

## Non functional requirements

- The presence of unit, integration and acceptance tests will positively appreciated.
- Use whatever you want to achieve this: MVC, hexagonal arquitecture, DDD, etc.
- A deep knowledge about SOLID, YAGNI or KISS would be positively evaluated.
- DevOps knowledge (GitHub Actions, Jenkins, etc.) would be appreciated too.
- It's important to find a balance between code quality and deadline; releasing a non functional application in time or a perfect application out of time may be negatively evaluated.
- Good and well-documented commits will be appreciated.
- Efficient and smart use of third party libraries will be positively appreciated.

---

Beyond the requirements of this test we want to see what you can do, feel free to show us your real potential and, the
most important part, have fun!





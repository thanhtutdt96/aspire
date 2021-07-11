# Documentation

## Overview

This project using Laravel 8, developed with PHP 7.4 and MySQL 5.7. The API authentication manipulates
by [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum#introduction)
with auth routes based on [Laravel Breeze](https://laravel.com/docs/8.x/starter-kits#laravel-breeze).

## Setup on Local

1. Rename the `.env.example` to `.env`, adjust the setting to match the MySQL setting on the local.
2. Run these commands to install necessaries project libraries:

```angular2html
composer install
yarn install
yarn dev
```

3. Navigate to project folder, then run the following commands, to generate project keys, create tables and seed data:

```angular2html
php artisan key:generate
php artisan migrate
php artisan db:seed
```

4. Run `yarn serve` to serve the project.

## Using the API

The api routes were declare in `routes/api.php`. The API endpoint should have the following
pattern `{API_URL}/api/v1/{API_NAME}`. For example on my local it will be `http://localhost:8000/api/v1/users` to get
the user list.

### Using with [Postman](https://www.postman.com/downloads/)

The easiest way to run all the API collections, is using Postman. There are 2 exported files for Postman collection and
environment, located in `postman` folder. To import it to Postman, following
this [guide](https://learning.postman.com/docs/getting-started/importing-and-exporting-data/). After importing
everything, the API Collections should be ready to be use. You can also adjust the api url settings, by clicking the eye
settings on the top right side of Postman.

Also, please noted to always run the Login API first, before running any of those API collections.

### Using with other tools

Here are the list of available API in the project. Please, noted to always run the Login API first, and include the
token from login to `Authorization`
header in every API requests. For example: `Authorization: Bearer 6|24yDDUDFFSUeTQtTGrAgCmRIPqk9sFgUZPSKE9sK`

1. **Login (email: thanhtutdt96@gmail.com / pass: YF4pfsHx)**
    - Endpoint: `/api/login`
    - Method: POST
    - Parameter:
        - email
        - password
    - Return:
        - token

2. **Get Users**
    - Endpoint: `/api/v1/users`
    - Method: GET
    - Return:
        - users

3. **Add User**
    - Endpoint: `/api/v1/users`
    - Method: POST
    - Parameter:
        - email
        - name
        - identity_number
        - birthday
        - gender
        - address
        - city
        - phone
        - password
    - Return:
        - message

4. **Show User**
    - Endpoint: `/api/v1/users/{id}`
    - Method: GET
    - Return:
        - user

5. **Delete User**
    - Endpoint: `/api/v1/users/{id}`
    - Method: DELETE
    - Return:
        - message

6. **Get Loans**
    - Endpoint: `/api/v1/loans`
    - Method: GET
    - Return:
        - loans

7. **Add Loan**
    - Endpoint: `/api/v1/loans`
    - Method: POST
    - Parameter:
        - user_id
        - package_id
        - purpose
        - base_amount
    - Return:
        - message

8. **Show Loan**
    - Endpoint: `/api/v1/loans/{id}`
    - Method: GET
    - Return:
        - loan

9. **Update Loan**
    - Endpoint: `/api/v1/loans/{id}`
    - Method: PUT
    - Parameter:
        - status
    - Return:
        - message

10. **Delete Loan**
    - Endpoint: `/api/v1/loans/{id}`
    - Method: DELETE
    - Return:
        - message

11. **Get Repayments**
    - Endpoint: `/api/v1/repayments`
    - Method: GET
    - Return:
        - repayments

12. **Show Repayment**
    - Endpoint: `/api/v1/repayments/{id}`
    - Method: GET
    - Return:
        - repayment

9. **Update Repayment**
    - Endpoint: `/api/v1/repayments/{id}`
    - Method: PUT
    - Parameter:
        - status
    - Return:
        - message

10. **Delete Repayment**
    - Endpoint: `/api/v1/repayments/{id}`
    - Method: DELETE
    - Return:
        - message

11. **Make Repayment**
    - Endpoint: `/api/v1/makeRepayment/{id}`
    - Method: POST
    - Parameter:
        - payment_method
        - note
    - Return:
        - repayment / message

12. **Get Packages**
    - Endpoint: `/api/v1/packages`
    - Method: GET
    - Return:
        - packages

13. **Add Package**
    - Endpoint: `/api/v1/packages`
    - Method: POST
    - Parameter:
        - interest_rate
        - weeks
        - arrangement_fee_rate
        - description
    - Return:
        - message

14. **Show Package**
    - Endpoint: `/api/v1/packages/{id}`
    - Method: GET
    - Return:
        - package

15. **Update Package**
    - Endpoint: `/api/v1/packages/{id}`
    - Method: PUT
    - Parameter:
        - interest_rate
        - weeks
        - arrangement_fee_rate
    - Return:
        - message

16. **Delete Package**
    - Endpoint: `/api/v1/packages/{id}`
    - Method: DELETE
    - Return:
        - message
    


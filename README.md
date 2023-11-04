# Loop - Mini Web Shop
Project: Mini Web Shop

Description: This project is a mini web shop built with Laravel. It includes features such as importing data of products and custmoers from CSV files, checkout, and order management.

## Requirements:

PHP 7.4+  
MySQL 8.0+  
Laravel 8  

## Installation:

1. Clone the project repository:  
    ```
    git clone https://github.com/your-username/mini-web-shop.git  
    ```
2. Install the project dependencies:
    ```
    composer install
    ```
3. Create a database for the project:
```mysql -u root -p```
```CREATE DATABASE mini_web_shop;```
4. Generate an encryption key for the project:
    ```
    php artisan key:generate
    ```
5. Set up the database configuration file:
```cp .env.example .env```
Edit the ```.env``` file and update the database credentials.
6. Follow the instructions as below:

    Migrate the database:
    ```
    php artisan migrate
    ```
    Import data from CSV files:  
    ```
    php artisan command:ImportData
    ```
    Start the development server:
    ```
    php artisan serve
    ```
## Usage:

Visit http://localhost:8000 in your web browser to check the web shop works properly.  

Consider this project just handle backend services of the webshop so it doesn't have any UI yet. 
At this moment the webshop has the ability to
Place your order and complete the payment process with some simple error handling.

## Deployment:

To deploy the project to a production server, you can use the following steps:

1. Caching routes and configurations:
    ```
    php artisan route:cache
    php artisan config:cache
    ```
    This will improve the performance of project.
2. Copy the deployment package to the production server.
3. Unpack the deployment package on the production server.
4. Update the .env file on the production server with the production database credentials.
5. Migrate the database:
    ```
    php artisan migrate
    ```
    Import data from CSV files:
    ```
    php artisan command:ImportData
    ```
Start the production server.
## Support:
If you need support with this project, please feel free to create an issue on the GitHub repository.  
Also please consider, this is a simple mini web shop  project and still there are lots of features that need to be implemented before the final production. I will be working on implementing features day by day and your opinions will be valuable to me. 

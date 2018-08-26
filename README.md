weatherservice

How To Run
1.    Clone the repository.

2.    Install dependencies:
    ```
    $ composer install
    ```
3.    Run the built-in web server:
    ```
    $ php artisan serve
    ```
4.    The wind resource should now be accessible by running a curl command:
    ```
    $ curl -x http://localhost:8000/api/v1/wind/89101
    ```
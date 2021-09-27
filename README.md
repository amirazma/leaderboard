### Leaderboard API Application 


To run the project, create a database name "leaderboard" in MySQL, with the following information:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leaderboard
DB_USERNAME=root
DB_PASSWORD=
```

In terminal, in folder path :   **leaderboard/laravel**

run this command:
```
php artisan serve
```
In new terminal tab, run migration command to create user table:
```
php artisan migrate
```
### List of the endpoints:

Example: http://127.0.0.1:8002/api/users/

*Make sure in your HTTP header, you have  **Accept application/json***

-  **GET**   /api/users  
  Lists all users order by points DESC.
-  ---------
- **GET**   /api/users/{id}
 List a user information (name, age, points, address) based on given ID.
- ---
- **POST**  /api/users/register
Register a user. The required fields are : name, age, points, address.

All fileds are mandatory.
- ----
- **PUT**   /api/users/{id}/update_point/{action}

Update a user points. 

Action : it can be  **increment** , or **decrement**

Example:  PUT  /api/users/2/update_point/increment

Increments the points of user ID 2, by 1 point.
- ----
- **DELETE**   /api/users/delete/{id}

Deletes a user based on given ID.

### Unit Test
I included 2 unit tests for this project, I haven't created a separate test database for this project, but in real project we should have a separate test database for unit testing.

Run this command for unit tests:
```
php artisan test
```

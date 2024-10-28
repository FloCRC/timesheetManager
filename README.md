# Timesheet Manager

### Description

This is an API for a simple timesheet manager using Laravel. This builds a database of Projects, Employees and Timesheets, with various endpoints.

### Instructions

- Fork this Repository
- Clone your forked repository
- Run "composer install" in terminal
- Run "php artisan key:generate" in terminal
- Create a local database with the name "timesheetManager"
- Create a new .env file based on the .env.example file
- In the .env file, change the database details as bellow:
  - DB_CONNECTION=mysql 
  - DB_HOST={YOUR HOST}{127.0.0.1}
  - DB_PORT=3306 
  - DB_DATABASE=timesheetManager 
  - DB_USERNAME={YOUR USERNAME} 
  - DB_PASSWORD={YOUR PASSWORD}
- Run "php artisan migrate" in terminal
- Run "php artisan db:seed" in terminal
- Run "php artisan serve --host=0.0.0.0" in terminal to host locally 

### Documentation of all endpoints can be found at /docs/api

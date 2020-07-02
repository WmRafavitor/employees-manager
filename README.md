## About

Application with a CRUD (Create, Read, Update and Delete) for employees, with the fields: name, email, sex, phone, role in the company, birth date, document, and the hired date. To access the employees features you need to log in.

## Screenshots

![Login](/screenshots/login.png?raw=true "Login")

![List](/screenshots/list.PNG?raw=true "List employees")

![Form](/screenshots/form.PNG?raw=true "Add or edit employee")

![Remove](/screenshots/remove.PNG?raw=true "Remove employee")

## How to run

The easier way to execute this application is using docker and docker-compose, execute the command below in the project folder:

`docker-compose up`

After the project is running execute the commands below to migrate the database and seed it.

`docker-compose exec api php artisan migrate --force`

`docker-compose exec api php artisan db:seed`

## Using the application

Open your browser on `http://localhost:8080` and if you execute the command do seed the database you can use `admin@user.com` as Login and `password` as password.

About our project
------------
This is a web application for a fitness club which the major purpose is to make training sessions schedule more organised.
It allows you to:
* Explore fitness club schedule
* View information about specific event with description, available places, 
* Book a place for a specific event (with email confirmation)
* View information about coaches like opinions and biography

Requirements
------------

* [Docker](https://store.docker.com/editions/community/docker-ce-desktop-windows)
* [Git](https://git-scm.com/)

Installation
-------------
#### 1. Download our repository:
```
git clone
```
#### 2. Go to the project root directory.
```
cd fitness-club
```
#### 3. Run docker containers with following commands:
```
docker-compose build
docker-compose up -d
```
This will build 3 containers:
* Web server - **nginx** latest stable version that runs on "85:80" port with *default.conf* placed in */docker/nginx/*
* PHP - 7.2
* Database - **PostgreSQL** that runs on default "5432" port
#### 4. Install symfony 4 with the following command:
```
docker exec -it fitness-club_php_1 composer install
```
#### 5. Go to your browser and type:
```
http://localhost:85/
```

Explore
-------------
#### Login as administrator:

Go to restricted area for example: **http://localhost:85/room/new** and log in. 

````
User: admin
Password: admin
````
Useful commands:
-------------
#### Open php container:
````
docker exec -it fitness-club_php_1 bash
````

#### Login to postgreSQL container:

````
docker exec -it postgres psql -U admin -W fitness_club
````
Password is: **root**

#### Add example data to database
In order to use this command you need to be in php container

````
php ./bin/console hautelook:fixtures:load
````

#### Scan code with CodeSniffer:
````
docker exec -it fitness-club_php_1 php ./vendor/bin/phpcs ./src/ --standard=psr2
````
* ./src/ - directory that need to be scanned 
* --standard=psr2 - specify standard

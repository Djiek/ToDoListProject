# ToDoListProject
ToDoListProject is an application for managing the daily tasks of the company.
***
*ici => badge codacy
***
## Technologies
***
A list of technologies used within the project:
* [Symfony]: Version 5.3
* [PHP]: Version 7.3.12
* [Doctrine]
* [git]  
* [mySql] 
* [composer]
***

## Installation
***
requires php 7.3.12 to run.
To install the project :

* To download the project, please clone the github project with the repository link :
```$ git clone https://github.com/Djiek/ToDoListProject ```
* Update your BDD credentials in ToDolistProject .env
```
$ composer install
$ php bin/console doctrine:database:create 
$ php bin/console doctrine:migrations:migrate
$ php -S localhost:8000 -t public
```

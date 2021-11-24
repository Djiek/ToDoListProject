# ToDoListProject
ToDoListProject is an application for managing the daily tasks of the company.
***
*  [![Codacy Badge](https://app.codacy.com/project/badge/Grade/363eabc2d5264644a20f4445b65f632d)](https://www.codacy.com/gh/Djiek/ToDoListProject/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Djiek/ToDoListProject&amp;utm_campaign=Badge_Grade)
***
## Technologies
***
A list of technologies used within the project:
*  Symfony: Version 5.3
*  PHP: Version 7.3.12
*  Doctrine
*  git  
*  mySql 
*  composer
***

## Installation
***
requires php 7.3.12 to run.
To install the project :

*  To download the project, please clone the github project with the repository link :
```$ git clone https://github.com/Djiek/ToDoListProject ```
*  Update your BDD credentials in ToDolistProject .env

```
$ composer install
$ php bin/console doctrine:database:create 
$ php bin/console doctrine:migrations:migrate
$ php -S localhost:8000 -t public
```

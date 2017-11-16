# IMS API
Made by Miagists for Miagists

## Stage API
[StageIMS](http://api.leonard.zone)

## Routes described in API.md

Pro-tip : Use postman collection/environment in /assets of the repository

## Todo
- General :
    - Database /!\
    - Documentation
        - Generate using Postman (collection in assets/)
        - API.md using cli

- Routes :
    - Students related
    - Convention related
    - Admin related | Admin-convention related

Based on [SlimREST](https://github.com/awurth/SlimREST) | Slim 3

## Stack
- [Eloquent ORM](https://github.com/illuminate/database)
- Authentication ([Sentinel](https://github.com/cartalyst/sentinel))
- Validation ([Respect](https://github.com/Respect/Validation) + [Slim Validation](https://github.com/awurth/slim-validation))
- Logs ([Monolog](https://github.com/Seldaek/monolog))
- Console commands for updating the database schema and creating users
- A RESTful router

## Install

### Load dependencies
Install dependencies using [Composer](https://getcomposer.org/) at the root of `serv/
``` bash
$ composer install
```

### Create database tables
``` bash
$ php bin/console db
```

### Create users
``` bash
$ php bin/console user:create
```
Use `--admin` option to set the user as admin

## CLI Features

### Dump routes
Execute the following command at the project root to print all routes in your terminal
``` bash
$ php bin/console routes
```

Use --markdown or -m option to display routes in markdown format, and write in API.md
``` bash
$ php bin/console routes -m > API.md
```

#### Pro-tip :
If you're using [Oh My Zsh](https://github.com/robbyrussell/oh-my-zsh), you can create an alias into your .zshrc to use the cli from anywhere :
``` bash
alias ims='f() { php rootToIMSAPI/bin/console $1. };f'
```

# IMS API
Made by Miagists for Miagists

Based on [SlimREST](https://github.com/awurth/SlimREST) | Slim 3

## Stack
- [Eloquent ORM](https://github.com/illuminate/database)
- Authentication ([Sentinel](https://github.com/cartalyst/sentinel))
- Validation ([Respect](https://github.com/Respect/Validation) + [Slim Validation](https://github.com/awurth/slim-validation))
- Logs ([Monolog](https://github.com/Seldaek/monolog))
- Console commands for updating the database schema and creating users
- A RESTful router

## CLI features
### Create database tables
``` bash
$ php bin/console db
```

### Create users
``` bash
$ php bin/console user:create
```
Use `--admin` option to set the user as admin

### Dump routes
Execute the following command at the project root to print all routes in your terminal
``` bash
$ php bin/console routes
```

Use --markdown or -m option to display routes in markdown format
``` bash
$ php bin/console routes -m > API.md
```

If you're using [Oh My Zsh](https://github.com/robbyrussell/oh-my-zsh), you can create an alias into your .zshrc :
``` bash
alias ims='f() { php rootToIMSAPI/bin/console $1. };f'
```

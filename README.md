# This project demonstrate work with ChainCommandBundle and BarBundle, FooBundle

ChainCommandBundle help you run your commands as chain of commands. You have opportunity to register console

commands from Symfony bundles as members of command chain. You define main command and append other command

as children with some priority. You can create one or several chains. When main command in chain is running

every command in chain will be accomplished in order your priority.


How to use
-------------------------

Just run make command

```yml
make init
```

This project use docker-compose

Or use php-cli commands 
```yml
docker-compose run --rm php-cli php bin/console chain:command 
```
```yml
docker-compose run --rm php-cli php bin/console bar:command 
```
```yml
docker-compose run --rm php-cli php bin/phpunit  
```



For adding command in chain of commands you should register command

as service with tag "chain_command", and head:"command_head_name" after that command is member of chain with head command name.

```yml
    tags:
        - { name: 'chain_command', head: 'head:command' }
```

You can also specify a priority tag, by default it is 0

```yml
    tags:
        - { name: 'chain_command', head: 'head:command', priority: 5 }
```
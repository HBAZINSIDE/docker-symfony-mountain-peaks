# 🐳 Docker + PHP 7.4 + MySQL + Nginx + Symfony 5

## Description

This is a complete stack for running Symfony 5 into Docker containers using docker-compose tool.

It is composed by 3 containers:

- `nginx`, acting as the webserver.
- `php`, the PHP-FPM container with the 7.4 PHPversion.
- `db` which is the MySQL database container with a **MySQL 8.0** image.

## Installation

1. 😀 Clone this rep.

2. Run `docker-compose up -d`

3. The 3 containers are deployed: 

```
Creating docker-symfony-montain-peaks_db_1    ... done
Creating docker-symfony-montain-peaks_php_1   ... done
Creating docker-symfony-montain-peaks_nginx_1  ... done
```

4. Run 'docker exec -it  docker-symfony-montain-peaks_php_1 bash'

5. Run 'bin/console d:s:u --force' 

## Access 

1. http://localhost/api/doc (to access the api documentation and swagger's sandbox for API operations)

2. http://localhost/web/mountain/peaks (to access create/read/update/delete web forms)
This part is a work in progress with no associated style sheets yet

## TODO

1. Add validation for the geographical Bounding Box sent data 
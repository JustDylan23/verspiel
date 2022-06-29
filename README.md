## Installing
### Docker
Starting the docker containers:
```shell
$ docker-compose up -d
```
### PHP
Connecting to the php docker container:
```shell
$ docker-compose exec php sh
```
Installing backend dependencies after connecting to the container for the first time
```shell
$ composer install
```
Creating database schema
```shell
$ php bin/console doctrine:schema:create
```
Loading data fixtures
```shell
$ php bin/console doctrine:fixtures:load -n
```
## Frontend
Installing frontend dependencies
```shell
$ yarn
```
Starting the dev server
```shell
$ yarn dev
```
Building for production
```shell
$ yarn build
```

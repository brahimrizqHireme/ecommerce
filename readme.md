# PHP symfony environment
Docker environment required to run Symfony (based on official php and mysql docker hub repositories).


## Requirements
* Docker version 18.06 or later
* Docker compose version 1.22 or later
* An editor or IDE
* MySQL Workbench

Note: OS recommendation - Linux Ubuntu based.

## Components
1. Nginx 1.23
2. PHP 8.2 fpm
3. MySQL 8
4. Symfony 6
5. RabbitMQ 3

## Setting up Docker and docker-compose
1.For installing Docker please follow steps mentioned on page [install on Ubuntu linux](https://docs.docker.com/install/linux/docker-ce/ubuntu/) or [install on Mac/Windows](https://docs.docker.com/engine/install/).

2.For installing docker-compose as `Linux Standalone binary` please follow steps on the page [install compose](https://docs.docker.com/compose/install/other/#install-compose-standalone) if you are using Linux OS.

Note: Please run next cmd after above step 2 if you are using Linux OS: `sudo usermod -aG docker $USER`

## Setting up DEV environment
1.You can clone this repository from GitHub or install via composer.

If you have installed composer and want to install environment via composer you can use next cmd command:
```bash
composer create-project systemsdk/docker-nginx-php-symfony example-app
```

2.Set another APP_SECRET for application in .env.prod and .env.staging files.

Note 1: You can get unique secret key for example [here](http://nux.net/secret).

Note 2: Do not use .env.local.php on dev and test environment (delete it if exist).

Note 3: If you want to change default web port/xdebug configuration you can create .env.local file and set some params (see .env file).

Note 4: Delete var/mysql-data folder if it exists.

3.Add domain to local 'hosts' file:
```bash
127.0.0.1    localhost
```

4.Configure `/docker/dev/xdebug-main.ini` (Linux/Windows) or `/docker/dev/xdebug-osx.ini` (MacOS) (optional):

- In case you need debug only requests with IDE KEY: PHPSTORM from frontend in your browser:
```bash
xdebug.start_with_request = no
```
Install locally in Firefox extension "Xdebug helper" and set in settings IDE KEY: PHPSTORM

- In case you need debug any request to an api (by default):
```bash
xdebug.start_with_request = yes
```

5.Build, start and install the docker images from your terminal:
```bash
make build
make start
make composer-install
```

6.Make sure that you have installed migrations / messenger transports:
```bash
make migrate
make messenger-setup-transports
```

7.Check and open in your browser next url: [http://localhost](http://localhost).

## Setting up STAGING environment locally
1.You can clone this repository from GitHub or install via composer.

Note: Delete var/mysql-data folder if it is exist.

If you have installed composer and want to install environment via composer you can use next cmd command:
```bash
composer create-project systemsdk/docker-nginx-php-symfony example-app
```

2.Build, start and install the docker images from your terminal:
```bash
make build-staging
make start-staging
```

3.Make sure that you have installed migrations / messenger transports:
```bash
make migrate-no-test
make messenger-setup-transports
```

## Setting up PROD environment locally
1.You can clone this repository from GitHub or install via composer.

If you have installed composer and want to install environment via composer you can use next cmd command:
```bash
composer create-project systemsdk/docker-nginx-php-symfony example-app
```

2.Edit docker-compose-prod.yml and set necessary user/password for MySQL and RabbitMQ.

Note: Delete var/mysql-data folder if it is exist.

3.Edit env.prod and set necessary user/password for MySQL and RabbitMQ.

4.Build, start and install the docker images from your terminal:
```bash
make build-prod
make start-prod
```

5.Make sure that you have installed migrations / messenger transports:
```bash
make migrate-no-test
make messenger-setup-transports
```

## Getting shell to container
After application will start (`make start`) and in order to get shell access inside symfony container you can run following command:
```bash
make ssh
```
Note 1: Please use next make commands in order to enter in other containers: `make ssh-nginx`, `make ssh-supervisord`, `make ssh-mysql`, `make ssh-rabbitmq`.

Note 2: Please use `exit` command in order to return from container's shell to local shell.

## Building containers
In case you edited Dockerfile or other environment configuration you'll need to build containers again using next commands:
```bash
make stop
make build
make start
```
Note 1: Please use next command if you need to build staging environment `make build-staging` instead `make build`.

Note 2: Please use next command if you need to build prod environment `make build-prod` instead `make build`.

## Start and stop environment
Please use next make commands in order to start and stop environment:
```bash
make start
make stop
```
Note 1: For staging environment need to be used next make commands: `make start-staging`, `make stop-staging`.

Note 2: For prod environment need to be used next make commands: `make start-prod`, `make stop-prod`.

## Additional main command available
```bash
make build
make build-test
make build-staging
make build-prod

make start
make start-test
make start-staging
make start-prod

make stop
make stop-test
make stop-staging
make stop-prod

make restart
make restart-test
make restart-staging
make restart-prod

make env-staging
make env-prod

make ssh
make ssh-root
make ssh-nginx
make ssh-supervisord
make ssh-mysql
make ssh-rabbitmq

make composer-install-no-dev
make composer-install
make composer-update

make info

make logs
make logs-nginx
make logs-supervisord
make logs-mysql
make logs-rabbitmq

make drop-migrate
make migrate
make migrate-no-test

make fixtures

make messenger-setup-transports

make phpunit
make report-code-coverage

make phpcs
make ecs
make ecs-fix
make phpmetrics
make phpcpd
make phpmd
make phpstan
make phpinsights

etc....
```
Notes: Please see more commands in Makefile

## Working on your project
1. For new feature development, fork `develop` branch into a new branch with one of the two patterns:
    * `feature/{ticketNo}`
2. Commit often, and write descriptive commit messages, so its easier to follow steps taken when reviewing.
3. Push this branch to the repo and create pull request into `develop` to get feedback, with the format `feature/{ticketNo}` - "Short descriptive title of Jira task".
4. Iterate as needed.
5. Make sure that "All checks have passed" on CircleCI(or another one in case you are not using CircleCI) and status is green.
6. When PR is approved, it will be squashed & merged, into `develop` and later merged into `release/{No}` for deployment.

Note: You can find git flow detail example [here](https://danielkummer.github.io/git-flow-cheatsheet).

## License
[The MIT License (MIT)](LICENSE)

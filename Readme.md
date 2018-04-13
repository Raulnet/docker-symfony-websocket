Docker Symfony Websocket
========
### Install
````bash
$ composer install
$ docker-compose up --build
````

### Init/Launch
- launch server Websocket
````bash
$ docker exec -it docker-symfony-websocket_api_1 bash
$ ./bin/console a:w
````
- Open file index.html on your browser
- Launch process command on new shell
````bash
$ docker exec -it docker-symfony-websocket_api_1 bash
$ ./bin/console a:r
````

### Result
- All process appear on Browser when is start/done
- All process message showing on process shell 
- All process connection appear on server Websocket Shell
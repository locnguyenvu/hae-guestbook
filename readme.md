# Hae Guestbook

## Setup

1. Start Nginx with config
```
server {
    listen       80;
    server_name  hae.local;

    root your_dir/hae/frontend;

    location / {
        index  index.html index.htm;
        try_files $uri $uri.html $uri/ =404;
    }
}

server {
    listen 80;
    server_name api.hae.local;

    add_header 'Access-Control-Allow-Origin' '*';
    add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS, PUT, DELETE';
    add_header 'Access-Control-Allow-Headers' 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,Authorization';

    location / {
        proxy_pass http://127.0.0.1:8000;
    }
}
```

2. Update host files `/etc/hosts`
```
127.0.0.1   hae.local api.hae.local
```

3. Init DB & insert mockup data
```
chmod +x setup
./setup initDb
```

5. Init composer autoload
```
composer install
```

6. Start Php server
```
cd ./backend
php -S 127.0.0.1:8000
```

## Web pages (Frontend)
1. Client http://hae.local
> Features:
> 1. List comments
> 2. Create comment

2. Admin http://hae.local/admin
> Login: admin/admin
> 
> Features:
> 1. Login
> 2. List comments
> 3. Edit comment
> 4. Delete comment

## Api (Backend)
Domain: http://api.hae.local

1. *GET* `/guestbook` : List all comments
2. *POST* `/guestbok` : Create comment
3. *POST* `/login` : Admin login
4. *GET* `/guestbook/{id}` : Get comment detail by id (*)
5. *PUT* `/guestbook/{id}` : Edit comment content (*)
6. *DELETE* `/guestbook/{id}` : Delete comment (*)

(*) Require authentication

## Code structure
`src/Core` : My framework ;)

`backend/index.php`: Webapp container + route config

`app/Controller/*`: Request controllers

`frontend/*`: html + js files

I used package `symfony/var-dumper` during development

```
.
├── app
│   ├── Controller
│   │   ├── AbstractController.php
│   │   ├── GuestBookController.php
│   │   └── IndexController.php
│   ├── WebApp.php
│   └── routes.php
├── backend
│   └── index.php
├── bootstrap.php
├── composer.json
├── composer.lock
├── config.php
├── db
│   └── schema.sqlite3
├── frontend
│   ├── admin.html
│   ├── index.html
│   └── js
│       ├── admin.js
│       ├── client.js
│       └── hae.js
├── readme.md
├── setup
├── src
    ├── Container
    │   └── Reference
    │       ├── ParameterReference.php
    │       └── ServiceReference.php
    ├── Container.php
    ├── Core
    │   ├── Config.php
    │   ├── DatabaseConnection.php
    │   ├── Entity.php
    │   ├── HttpRequest.php
    │   ├── HttpResponse.php
    │   ├── Repository.php
    │   └── Router.php
    ├── Exception
    │   ├── ContainerException.php
    │   ├── MethodNotAllowedException.php
    │   ├── ParameterNotFoundException.php
    │   ├── RouteNotFoundException.php
    │   └── ServiceNotFoundException.php
    ├── GuestBook
    │   ├── GuestBook.php
    │   └── GuestBookRepository.php
    └── helper_functions.php

```

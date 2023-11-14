# users-widget-app 
Тестовый проект на Laravel по генерации пользовательского png виджета на Laravel


## Laravel Sail
Для работы с приложением в локальном окружении используется Laravel Sail
https://laravel.com/docs/10.x/installation#choosing-your-sail-services

Для запуска команд sail выполните `.vendor/bin/sail <options>`
`alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'` - определение алиаса для быстрого запуска команд
через `sail`, чтобы каждый раз не указывать путь до команды.
`make add-alias` - автоматически добавит алиас.


## Разворачивание в локальном окружении
`sail build --no-cache` команда для обновления образов docker-контейнеров
`sail`  команда - для отображения списка доступных команд
`sail up` - Чтобы запустить все контейнеры Docker, определенные в файле docker-compose.yml
`sail up -d` - запустить все контейнеры Docker в фоновом режиме
'Ctrl + C' - остановить все контейнеры.
Чтобы остановить выполнение контейнера в фоновом режиме (опция `-d`), вы можете использовать команду: `sail stop`
Сайт доступен по адресу: [http://localhost/](http://localhost/)
Сгенерировать ключ приложения `docker exec -w /var/www/lazada-voluum-integrator.api php_svi php artisan key:generate`
С Sail `sail php artisan key:generate`

Поскольку Sail построен на Docker, вы можете настроить в нём почти всё.
Чтобы опубликовать Docker-файлы Sail, и внести в них необходимые вам изменения, вы можете выполнить
команду `sail artisan sail:publish`

После выполнения этой команды файлы Dockerfiles и другие файлы конфигурации, используемые Laravel Sail, будут помещены в
каталог docker в корневом каталоге вашего приложения.
После настройки вашей установки Sail вы можете изменить имя образа для контейнера приложения в файле docker-compose.yml
вашего приложения.
После этого пересоберите контейнеры приложения с помощью команды `sail build --no-cache`.
Назначение уникального имени образу приложения особенно важно, если вы используете Sail для разработки нескольких
приложений Laravel на одной машине.

### Проблема занятого 80 порта
Может возникнуть ситуация, когда при запуске приложения возникает ошибка `listen tcp4 0.0.0.0:80: bind: address already in use`.
Для освобождения порта выполните команду: `sudo systemctl stop nginx`

### PHP
В настоящее время Sail поддерживает обслуживание вашего приложения через PHP 8.1, PHP 8.0 или PHP 7.4.
Версия PHP по умолчанию, используемая Sail, в настоящее время - PHP 8.1.
О том как сменить версию PHP читай [здесь](https://laravel.su/docs/8.x/sail#sail-php-versions)

После обновления файла docker-compose.yml вашего приложения вы должны обновить образы контейнеров:

`sail build --no-cache`
`sail up`

О том как предоставить доступ к сайту читай [здесь](https://laravel.su/docs/8.x/sail#sharing-your-site)

### [Выполнение команд через sail](https://laravel.su/docs/8.x/sail#executing-sail-commands)
https://laravel.su/docs/8.x/sail#executing-sail-commands

#### Выполнение Artisan команд
- `sail artisan queue:work`

#### Выполнение Node/NPM команд
- `sail node --version`
- `sail npm run prod`

#### Контейнер CLI
- `sail shell` - запустить сеанс Bash в контейнере приложения.
- `sail root-shell`
- `sail tinker` - Чтобы запустить новый сеанс Laravel Tinker

### Composer

Команды Composer могут быть выполнены с помощью команды composer. Контейнер приложения Laravel Sail содержит Composer
2.x:
`sail composer require laravel/sanctum`

#### Установка зависимостей Composer для существующих приложений
Пример установки зависимости для конкретной версии PHP:
`docker run --rm \
-u "$(id -u):$(id -g)" \
-v $(pwd):/var/www/html \
-w /var/www/html \
laravelsail/php81-composer:latest \
composer install --ignore-platform-reqs`

## [Отладка с Xdebug](https://laravel.su/docs/8.x/sail#debugging-with-xdebug)
Для тестирования из Postman достаточно добавить параметр ```?XDEBUG_SESSION_START=PHPSTORM``` в адрес запроса
Либо добавить куки ```XDEBUG_SESSION=PHPSTORM; Path=/;```

### Настройка IP хоста для Linux
Внутренняя переменная окружения `XDEBUG_CONFIG` определяется как `client_host=host.docker.internal`,
чтобы Xdebug был правильно настроен для Mac и Windows (WSL2).
Хост host.docker.internal существует только в системах под управлением Docker Desktop, т.е. Mac и Windows.
Если ваша локальная машина работает под управлением Linux, вам нужно будет вручную определить эту переменную окружения.

Во-первых, вы должны определить правильный IP-адрес хоста для добавления в переменную окружения, выполнив следующую
команду.
Обычно <container-name> должно быть именем контейнера, обслуживающего ваше приложение:

`docker inspect -f {{range.NetworkSettings.Networks}}{{.Gateway}}{{end}} lvi_php`
После того как вы получили IP-адрес хоста, на котором развёрнут Docker, вы должны определить
переменную `SAIL_XDEBUG_CONFIG` в файле .env вашего приложения:

`SAIL_XDEBUG_CONFIG="client_host=<host-ip-address>"`
Также нужно добавить:
`SAIL_XDEBUG_MODE=develop,debug,coverage`

### Отладка Artisan-команд
Для запуска Artisan-команд с включённым Xdebug используйте команду sail debug:

#### Run an Artisan command without Xdebug...
- `sail artisan migrate`

#### Run an Artisan command with Xdebug...
- `sail debug migrate`

## Интеграция с почтовым сервисом рассылок MailHog

В локальном окружении использован тестовый сервис MailHog в локальном окружении доступен по
адресу [http://localhost:8025/](http://localhost:8025/)

## Хелперы

### Очистка кеша системы при внесении изменений в конфигурацию
`php artisan cache:clear && php artisan route:clear && php artisan config:clear && php artisan view:clear`
+ `supervisorctl restart all`
  или `sail php artisan cache:clear && sail php artisan route:clear && sail php artisan config:clear && sail php artisan view:clear`
  для sail
  Перезагрузка классов  `composer dump-autoload` или `sail composer dump-autoload`

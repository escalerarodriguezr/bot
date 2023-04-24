# PRUEBA HR BOT FACTORY

## Resolución

La prueba se ha resuelto con Symfony 6.1 y se ha implementado un diseño aplicando Arquitectura Hexágonal y DDD.

Para la Authentication se ha implementado Json Web Tokens (JWT) (https://github.com/lexik/LexikJWTAuthenticationBundle).

En la ruta /doc, se incluye la documentación de la Colección de Postman con endpoints de ejemplo.

En la ruta  /bot se incluye de la aplicación.

El comando de instalacíon de los clientes genera los siguientes clientes:

-Cliente A
    Credenciales:
        "username": "clientA@bot.com",
        "password": "client_a_password"

-Cliente B
    Credenciales:
        "username": "clientB@bot.com",
        "password": "client_b_password"

-Cliente C
    Credenciales:
        "username": "clientC@bot.com",
        "password": "client_c_password"

## Filtro

Los filtros dinámicos que se pueden emplear tienen el siguente patrón:

Para un valor único

?[campo]Condition/[condition]=[value]


Las campos posibles son: 'name', 'lastName', 'location', 'age', 'category', 'active', 'createdAt', 'updatedAt'

El formato de createdAt y updateAt debe ser del tipo: 'Y-m-d H:i:s'

Las condiciones posibles son: 'EqualTo', 'NotEqualTo', 'GreaterThan', 'LessThan';

Los únicos campos que aceptan utilizar condiciones de rango: 'GreaterThan', 'LessThan' son: 'age', 'createdAt', 'updatedAt'

El separador de valores múltiples debe ser : '-|-'

Por tanto, un filtro para valores múltiples sería del tipo:

?[campo]Condition/[condition]=[value1-|-value2-|-value3]

Ejemplos de filtros:

http://localhost:250/api/v1/users?activeConditionNotEqualTo=false

http://localhost:250/api/v1/users?createdAtConditionGreaterThan=2023-04-21 11:43:03&nameConditionEqualTo=alejandro

http://localhost:250/api/v1/users?createdAtConditionGreaterThan=2023-04-21 11:43:03

http://localhost:250/api/v1/users?nameConditionEqualTo=alejandro-|-claudia

http://localhost:250/api/v1/users?nameConditionNotEqualTo=alejandro-|-claudia

http://localhost:250/api/v1/users?activeConditionNotEqualTo=false

En caso de que un filtro no tenga un formato correcto, se devuelve un 404 informando del error.



## Instalacción usando Make file
1-Levantar infraestructura

- `cd bot` entrar en la carpeta de la app
- `make build` hacer el build de la infraestructura con docker
- `make run` levantar los contendores
- `make restart` parar y levantar los contenedores

2-Instalar depedencias con composer

- `make prepare` instalar las dependencias con composer 

3-Generar private.pem y public.pem para los JWT

- `make generate-ssh-keys` generar los archivos private.pem y public.pem para los JWT

4-Migrar base de datos de desarrollo y de test

- `make migrate-database` migrar las base de datos de desarrollo
- `make migrate-database-tests` migrar la base de datos de test

5-Instalar Cliente A, Cliente B y CLiente Cliente Cliente C

- `make install-clients` instalar los Clientes Client A, Client B y Client C

6-Lanzar la suite de tests

- `make all-tests` lanzar la suite de tests

7-Comandos para entrar al contenedor de PHP y para los contenedores

- `make ssh-be` entrar al PHP container bash si es necesario
- `make stop` parar los contenedores


## Instalación sin Makefile
````shell

1-Levantar infraestructura

$ docker network create bot-network
$ U_ID=$UID docker-compose up -d --build

2-Instalar depedencias con composer

$ U_ID=$UID docker exec --user $UID -it bot-be composer install --no-scripts --no-interaction --optimize-autoloader 

3-Generar private.pem y public.pem para los JWT

$ U_ID=$UID docker exec -it --user $UID bot-be mkdir -p config/jwt
$ U_ID=$UID docker exec -it --user $UID bot-be openssl genrsa -passout pass:385575d5de21265084cff0be44ebeca9 -out config/jwt/private.pem -aes256 4096
$ U_ID=$UID docker exec -it --user $UID bot-be openssl rsa -pubout -passin pass:385575d5de21265084cff0be44ebeca9 -in config/jwt/private.pem -out config/jwt/public.pem
$ U_ID=$UID docker exec -it --user $UID bot-be chmod 644 config/jwt/private.pem

4-Migrar base de datos de desarrollo y de test

$ U_ID=$UID docker exec -it --user $UID bot-be bin/console doctrine:migrations:migrate -n
$ U_ID=$UID docker exec -it --user $UID bot-be bin/console doctrine:migrations:migrate --env=test -n

5-Instalar Cliente A, Cliente B y CLiente Cliente Cliente C

$ U_ID=$UID docker exec -it --user $UID bot-be bin/console app:install-initial-clients


6-Lanzar la suite de tests

$ U_ID=$UID docker exec --user $UID -it bot-be bin/phpunit

7-Comandos para entrar al contenedor de PHP y para los contenedores

$ U_ID=$UID docker exec -it --user $UID bot-be bash
$ U_ID=$UID docker-compose stop
````

## Stack:
- `NGINX 1.19` container
- `PHP 8.1.1 FPM` container
- `MariaDB 10.7.1` container + `volume`

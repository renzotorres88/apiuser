# ApiUser - Documentación

## Funcionalidad

La API permite gestionar un perfil de usuario básico.

A través de la misma los usuarios pueden:
* Traer su Información
* Borrar su Información
* Modificar su Información
* Subir una imagen

## Requerimientos

Se utilizó el framework Laravel, versión 5.8, para su desarrollo. Los datos que se generan a partir de la misma son almacenados en una base de datos MySQL.

### Framework
* PHP >= 7.1.3
* BCMath PHP Extension
* Ctype PHP Extension
* JSON PHP Extension
* Mbstring PHP Extension
* OpenSSL PHP Extension
* PDO PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension
* Composer

### Base de Datos
* MySQL >= 5.6


## Instalación y Configuración

1. Descargar el archivo .zip o clonar el repositorio mediante el comando git clone git@github.com:renzotorres88/apiuser.git
2. Crear la base de datos que se utilizará en la API.
3. Crear el archivo .env en el root del proyecto a partir del archivo proporcionado en la descarga, .env.example. Dentro de éste, setear las credenciales necesarias para la conexión con la base de datos creada en el punto anterior.<br/>
DB_CONNECTION=mysql<br/>
DB_HOST=\<host\><br/>
DB_PORT=3306<br/>
DB_DATABASE=\<name_base_de_datos\><br/>
DB_USERNAME=\<username DB\><br/>
DB_PASSWORD=\<contraseña DB\><br/>
4. Ejecutar el comando composer update, para descargar todas las dependencias necesarias.
5. Correr el comando php artisan migrate para crear la tabla de usuarios en la base de datos.
6. La API está instalada y configurada para utilizarse

## Descripción y EndPoints de la API

## add-user
#### Creación del perfil del usuario

* URL: http://localhost/api/add-user
* Método: POST
* Parámetros:
    * name (requerido)
    * email (requerido y único)
* Validaciones
    * Email único
* Respuesta (Json)<br>
    {
        "message": "User added successfully",
        "user data": "{\<datos del usuario\>}"
    }

## get-user-data
#### Método que retorna los datos del usuario solicitado

* URL: http://localhost/api/get-user-data/{userId}
* Método: GET
* Parámetros:
    * id del usuario(requerido)
* Validaciones
    * Sin validaciones
* Respuesta (Json)<br>
    {
        "user data": "{\<datos del usuario\>}"
    }

## update-user-data
#### Método que se utiliza para actualizar los datos del usuario solicitado

* URL: http://localhost/api/update-user-data/{userId}
* Método: PUT
* Parámetros:
    * id del usuario(requerido)
    * name (requerido)
    * email (requerido)
* Validaciones
    * Email único. En caso de editar el email del usuario, el mismo no debe existir en la base de datos.
* Respuesta (Json)<br>
    {
        "message": "User data updated successfully."
    }

## delete-user
#### Eliminación de un usuario

* URL: http://localhost/api/delete-user/{userId}
* Método: DELETE
* Parámetros:
    * id del usuario(requerido)
* Validaciones
    * Sin validaciones.
* Respuesta (Json)<br>
    {
        "message": "User deleted successfully."
    }

## upload-user-image
#### Método que permite subir una imagen asociada a un usuario

* URL: http://localhost/api/upload-user-image/{userId}
* Método: POST
* Parámetros:
    * id del usuario(requerido)
    * imagen (requerido - tipo 'file')
* Validaciones
    * Tamaño máximo de la imagen - 2048 bytes.
    * El archivo subido debe ser una imagen.
    * Formatos adminitidos: jpeg, png, jpg y gif.
* Respuesta (Json)<br>
    {
        "message": "Image upload successfully",
        "url": "\<url-image\>"
    }



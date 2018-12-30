# PHP2_AlejandroPueblaHolguin

# Asignatura: *Back-end con Tecnologías de Libre Distribución (PHP)*
> ### Alejandro Puebla Holguin
## Segunda práctica de PHP
#### [Máster en Ingeniería Web por la U.P.M.](http://miw.etsisi.upm.es)

## Tecnología utilizadas
* PHP
* Doctrine
* Composer
* XAMPP
* Swagger
* Symfony

## Objetivo
* Progresar en el conocimiento del lenguaje de scripting *PHP* y el *ORM Doctrine*. Además de familiarizarse con el desarrollo
de aplicaciones web completas integrando tecnologías del lado de cliente con el procesamiento en el lado servidor.

## Enunciado
* En esta segunda tarea se pretende familiarizar al alumno con las especificaciones y el desarrollo de una API REST [1] . Para ello se proporciona una especificación y un esqueleto de desarrollo basado en Symfony y se desarrollará una API completa empleando como base el modelo de datos utilizado en la práctica anterior. 

## Se pide
* Todas las operaciones consumirán y generarán siempre documentos en formato JSON. Análogamente, las operaciones de tipo POST y PUT recibirán también los datos en notación JSON.
* Para reducir el tamaño del fichero enviado se recomienda eliminar o excluir directorios innecesarios como /vendor, /var/logs y /var/cache.
* En la especificación se incluyen restricciones de acceso a las diferentes operaciones (códigos 401 y 403). Se propone la implementación de los mismos como ejercicio opcional.
* La implementación de esta tarea se realizará empleando el framework Symfony4 [2]. Se podrán además utilizar cuantos bundles públicos se consideren oportunos. Adicionalmente -para hacer más sencilla y versátil la gestión de los datos- obligatoriamente se deberá emplear un ORM (p.ej. Doctrine, Propel, Eloquent, etc). Doctrine 2 es un Object-Relational Mapper para PHP 5.4+ que proporciona persistencia transparente para objetos PHP. Utiliza el patrón Data Mapper con el objetivo de obtener un desacoplamiento completo entre la lógica de negocio y la persistencia de los datos en un SGBD.
* En el envío se debe incluir una memoria explicativa del trabajo desarrollado en la que adicionalmente se indicará, en caso de ser necesario, las modificaciones o mejoras realizadas sobre la especificación.
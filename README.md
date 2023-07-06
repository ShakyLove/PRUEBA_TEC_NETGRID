# PRUEBA-TECNICA-NETGRID
Buenas dia, comparto prueba tecnica.

Recomendaciones para la ejecucion del proyecto:
1. En la capeta front ejecutar el comando npm install y luego el comando npm run dev
2. En la carpeta back ejecutar el comando composer install
---- Seguir los pasos a continuacion o simplemente importar el archivo sql del proyecto adjuntado a la prueba
---- Si se importó el archivo sql se puede ingresar al sistema con el siguiente usuario:
usuario: admin
contraseña: admin
1. Crear una base de datos en mysql con el nombre prueba_netgrid
2. Cambiar la conexion a la base de datos en el archivo .env
3. Ejecutar el comando php artisan migrate
4. Ejecutar el comando php artisan db:seed --class=StateTaskSeeder
5. Ejecutar el comando php artisan db:seed --class=RoleSeeder
6. Por ultimo el comando php artisan serve

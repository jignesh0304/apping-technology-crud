#RUN BELOW COMMAND ONE BY ONE
==================================================
composer install 
php artisan config:clear 
php artisan cache:clear 
php artisan route:clear 
php artisan view:clear 
php artisan migrate 
php artisan db:seed


#ADMIN LOGIN DETAILS
===============================================
admin@admin.com
123456

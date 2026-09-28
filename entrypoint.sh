#!/bin/bash
composer i
php artisan migrate

#php artisan db:seed

npm install

composer run dev --host 0.0.0.0


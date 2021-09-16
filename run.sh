#!/bin/bash

echo Uploading Application container
docker-compose up -d

echo Installing dependencies
docker exec -it app-siscred composer install

echo Generating key
docker exec -it app-siscred php artisan key:generate

echo Making migrations
docker exec -it app-siscred php artisan migrate

echo Seeding database
docker exec -it app-siscred php artisan db:seed

echo Seeding dataTest
docker exec -it app-siscred php artisan db:seed --class TestDataSeeder

echo generating storage link
docker-compose exec app-siscred php artisan storage:link

echo Information of new containers
docker ps -a

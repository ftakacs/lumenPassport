Lumen Passport
==================================

# Setup #

  * Clonar o projeto e copiar o arquivo .env.example para .env
  * Subir os containers do docker: docker-compose up -d
  * Acessar o bash do container php: docker exec -it lumen-php-fpm sh
  * Instalar as dependências via composer install
  * Rodar as migrações: php artisan migrate
  * Instalar os clientes do Passport: php artisan passport:install

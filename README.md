## Test Project

Este projeto está usando as seguintes tecnologias e padrões:

- Laravel 12
- PHP 8.2
- Domain Driven Design
- SOLID
- Docker / Docker Compose
- PHP Unit
- Scramble (OpenAPI)
- Laravel Sanctum: Autenticação com Tokens


## Desenvolvimento

Para realizar o teste e desenvolvimento local faça os seguintes passos


### Criar .env

```bash
cp .env.example .env 

# Lembre de ajustar suas variaveis de ambiente para conectar no banco de dados
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=database
DB_USERNAME=root
DB_PASSWORD=root

```

### Iniciar o container

```bash
# Fazer o primeiro Build do projeto com os containers (PHP + MySQL)
docker-compose up --build -d

# Depois de realizar o build os containers serão iniciados automaticamente, use este comando pra ver os containers
docker ps -a

# Desligar os containers
docker-compose down

# Quando precisar rodar os containers novamente não precisa buildar, use o seguinte comando
docker-compose up -d
```

### - Instalar as dependencias do projeto

```bash
composer install
```

### Realizar Migrations / Seeders

```bash
## As migrations devem ser feitas dentro do container do backend, que possui a integração com o banco mysql que está em outro container
docker exec -it {{ id_do_container }} sh

php artisan migrate

# Se precisar popular o banco use os seeders
php artisan migrate:fresh --seed
```

## Testes unitários / Integração com PHP Unit

Rodando apenas um teste específico

```bash
php artisan test --filter DatabaseCheckTest
```

Rodando todos os testes

```bash
php artisan test
```

## Documentação da API

```
Usuário para uso na documentação da API

Email: testing@email.com
Senha: password
```

A documentação da API pode ser encontrada em: http://localhost:8002/docs/api

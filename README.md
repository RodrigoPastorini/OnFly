# Onfly

**Onfly** é um sistema simples desenvolvido com Laravel, SQLite e PHP que permite cadastrar usuários, fazer login e criar pedidos de viagem.

## Tecnologias Utilizadas

- Laravel
- SQLite
- PHP

## Instalação

Para rodar o projeto, siga os passos abaixo:

1. Baixe ou clone o repositório em sua máquina local.
2. Abra a pasta do projeto `Onfly` na sua IDE preferida.
3. Instale as dependências do projeto executando o comando:
    ```bash
    composer install
    ```
4. Execute as migrations para criar as tabelas no banco de dados:
    ```bash
    php artisan migrate
    ```
5. Inicie o servidor local do Laravel:
    ```bash
    php artisan serve
    ```

Agora você pode acessar a aplicação localmente em `http://127.0.0.1:8000`.

## Como Usar

- **Cadastro de Usuários**: A aplicação permite a criação de novos usuários.
- **Login**: Usuários registrados podem fazer login.
- **Pedidos de Viagem**: Após o login, é possível criar pedidos de viagem.

## Testes Unitários

Para rodar os testes unitários, use o seguinte comando:

```bash
run-units
```

**Observação**: Para testar as rotas de API, você pode importar o arquivo de exportação do Insomnia incluído no projeto. Isso irá fornecer os endpoints e dados necessários para interagir com a aplicação.

Rotas:
## Criação de usuario:
curl --request POST \
  --url http://127.0.0.1:8000/user/register \
  --header 'Accept:  application/json' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/10.0.0' \
  --data '{
  "name": "John Doe",
  "email": "johndoe@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
'
## Login
curl --request POST \
  --url http://127.0.0.1:8000/user/login \
  --header 'Accept:  application/json' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/10.0.0' \
  --data '{
    "email": "johndoe@example.com",
    "password": "password123"
}
'

## Order Create

curl --request POST \
  --url http://127.0.0.1:8000/orders/register \
  --header 'Accept:  application/json' \
  --header 'Authorization: Bearer ' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/10.0.0' \
  --data '{
	"destination": "Dallas",
	"departure":"1990-05-15",
	"return":"1990-05-15"
}
'

## Order Cancelled
curl --request PATCH \
  --url http://127.0.0.1:8000/orders/1/cancel \
  --header 'Accept:  application/json' \
  --header 'Authorization: Bearer ' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/10.0.0' \
  --data '
'
## Order Aproved
curl --request PATCH \
  --url http://127.0.0.1:8000/orders/2/approve \
  --header 'Accept:  application/json' \
  --header 'Authorization: Bearer ' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/10.0.0' \
  --data '
'

## Order detail
curl --request GET \
  --url http://127.0.0.1:8000/orders/2/detail \
  --header 'Accept:  application/json' \
  --header 'Authorization: Bearer ' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/10.0.0' \
  --data '
'

## Order List
curl --request GET \
  --url http://127.0.0.1:8000/orders/list \
  --header 'Accept:  application/json' \
  --header 'Authorization: Bearer ' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/10.0.0' \
  --data '{
	"status" : ""
}
'






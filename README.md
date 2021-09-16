## Projeto


## Equipe

- [Carlos Emídio](https://github.com/carlosemidio)

## Requisitos
- Docker e Docker Compose

## Como rodar o projeto

- 1 - Baixe ou clone o projeto
- 2 - Na pasta raiz do projeto: copie e renomeie o arquivo .env.exemple para .env
   - Exemplo terminal linux/macOS: `cp .env.example .env` 
- 3 Abra o .env e mude os valores que estão entre `[]` para valores que julgar convenientes
    ```
    - APP_NAME=[App_name]
    - APP_ENV=dev
    - APP_KEY=
    - APP_DEBUG=true
    - APP_URL=http://localhost

    - LOG_CHANNEL=stack

    - DB_CONNECTION=mysql
    - DB_HOST=db
    - DB_PORT=3306
    - DB_DATABASE=[dbname]
    - DB_USERNAME=[db_user]
    - DB_PASSWORD=[db_password]

    - USER=[usuario com permissão para acessar o docker]
    - UID=[id do usuario]

    
- 4 Execute o comando `sh run.sh` e espere até que o docker-compose tenha baixado todas a imagens e subido todos os conteineres necessários para a execução do projeto.
       
5 - Agora é só acessar o site no http://localhost

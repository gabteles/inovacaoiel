# Inovação IEL
Instruções para instalação da solução em ambiente de produção.

## Prerequisitos
- PHP >= 5.6.24
- Apache ou Nginx c/ extensão PHP Instalada
- Extensões `pdo` e `pdo_mysql` instaladas
- Banco de dados MySQL
- [Composer](https://getcomposer.org/)

## Instalação
- Criar banco de dados vazio para a aplicação (ex.: `inovacaoiel`)
- Setar o valor da variável de ambiente `CLEARDB_DATABASE_URL` para o endereço do banco no formato `mysql://USERNAME:PASSWORD@ADDRESS:PORT/DATABASE_NAME?reconnect=true`
- Executar `composer install` para instalar as dependências da aplicação
- Executar `vendor/bin/phpmig migrate` para inicializar o banco de dados
- Para alterar os dados de envio de email, alterar o arquivo `src/settings.php` com as configurações desejadas na chave `mailer`.
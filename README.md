# Short Project (Guardaê)

Guardaê é um projeto desenvolvido com o objetivo de facilitar o manuseio de URLs longas na internet. Sem utilizar banco de dados, o tempo de resposta e redirecionamento levam milisegundos, além da segurança, no armazenamento dos dados.

###### Componentes necessários para a instalação do sistema:

- PHP 7.4+
- Servidor Apache
- Composer 2+

## Instalação do sistema

***Executar comando via terminal:***

```sh
composer install
```

***Regras de Apache `(.htaccess)`:***

```apache
Options +FollowSymLinks
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [NC,L,QSA]
```

## Tarefa Cron

O cron do sistema garante a segurança dos links encurtados. Alguns servidores web alteram a permissão de pastas e arquivos em reinicializações ou migrações, a tarefa cron se encarrega de controlar isso.

| Versão | Frequência de Tarefas Cron |
| ------ | ------ |
| v1.0+ | A cada 5 minutos ou com a frequência permitida pelo provedor de hospedagem (mínimo uma vez por hora). |

***Comandos:***
```sh
*/5 * * * * php -q {dirshort}/cronjobs/folders.php
```
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

## Erros

O sistema irá ignorar todos os avisos não críticos e tentar concluir todas as operações solicitadas em sua configuração padrão. Se a condição de erro for grave e continuar a executar a operação solicitada, sendo prejudicial ou impossível, o sistema exibirá uma mensagem de erro amigável. Isso é consistente com o comportamento da maioria dos sites de produção voltados para o público.

| Código de Erro | Tipo | Classificação | Descrição |
| ------ | ------ | ------ | ------ |
| -25 | error | normal | Erro ao alterar permissões de arquivo |
| -20 | error | normal | Custom Short ja esta sendo utilizado |
| -15 | error | normal | Falha ao escrever em arquivo |
| -14 | error | normal | Falha ao atualizar valor em arquivo |
| -13 | error | normal | Falha ao atualizar número de cliques |
| -12 | error | normal | Falha ao alterar/definir senha |
| -11 | error | normal | Falha ao alterar privacidade |
| -10 | error | normal | Falha ao abrir/criar arquivo |
| null | warning | global | Erro ao realizar processo |
| 0 | success | global | Processo concluido com sucesso |
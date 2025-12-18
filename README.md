# Projeto E-commerce

Projeto desenvolvido do zero no [Curso de PHP 7](https://www.udemy.com/curso-completo-de-php-7/) disponível na plataforma da Udemy e no site do [HTML5dev.com.br](https://www.html5dev.com.br/curso/curso-completo-de-php-7).

Template usado no projeto [Almsaeed Studio](https://almsaeedstudio.com)

## Como usar

### Clone

```sh
git clone https://github.com/hcodebr/ecommerce.git;
cd ecommerce;
```

### Instalar dependencias

```sh
composer update;
```

### Subir banco de dados mysql no docker

```sh
docker compose up -d --build;
```

### Parâmetros para Host Virtual

```conf
<VirtualHost *:80>
    ServerAdmin webmaster@hcode.com.br
    DocumentRoot "C:/xampp/htdocs/prato_cheio"
    ServerName www.prato.com.br
    ErrorLog "logs/dummy-host2.example.com-error.log"
    CustomLog "logs/dummy-host2.example.com-access.log" common
	<Directory "C:/xampp/htdocs/prato_cheio">
        Require all granted

        RewriteEngine On

        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
	</Directory>
</VirtualHost>
```
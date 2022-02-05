Při instalaci jsme postupovali podle oficiálního tutoriálu ze stránek Laravelu:
https://laravel.com/docs/8.x/installation

Instalovali jsme verzi Laravel 8
Byl použit i starter kit pro Laravel - Breeze:
https://laravel.com/docs/8.x/starter-kits

Pokud by se Vám instalace podle oficiálních zdrojů nepovedla, přikládáme vlastní podrobný návod instalace na linuxový server.

Požadavky:
    Linux:
        PHP 8.0
        Composer 1.10.1
        MySQL
        Npm 8.1.0

Instalace na novem serveru
    Digital ocean - příklad - droplet - ubuntu server:
        # php
        sudo apt install software-properties-common
        sudo add-apt-repository ppa:ondrej/php
        sudo apt update
        sudo apt install php8.0-cli php-xml php8.0-fpm
        sudo nano /etc/apt/sources.list
            add:
                deb http://archive.ubuntu.com/ubuntu bionic main universe
                deb http://archive.ubuntu.com/ubuntu bionic-security main universe
                deb http://archive.ubuntu.com/ubuntu bionic-updates main universe
        sudo apt-get install php-mbstring php-curl php-mysql

        # npm
        sudo apt-get install npm
        sudo npm install n -g
        sudo n stable
        PATH="$PATH"

        # mysql
        sudo apt update
        sudo apt install mysql-server

        sudo /etc/init.d/mysql start
        sudo mysql_secure_installation
        sudo mysql
            CREATE DATABASE db;
            CREATE USER 'jan'@'localhost' IDENTIFIED BY 'password';
            GRANT ALL PRIVILEGES ON *.* TO 'jan'@'localhost' WITH GRANT OPTION;
            FLUSH PRIVILEGES;

        # composer
        apt install composer


        # nahrání zdrojových souborů
        vytvoření laravel projektu https://laravel.com/docs/8.x/installation
	https://laravel.com/docs/8.x/starter-kits
        nahrazení odevzdaných souborů => př. WinScp
        store in tar, upload, tar -xvf itu.tar

        # nginx
        mkdir /var/www
        sudo apt install nginx

        sudo mv ~/itu /var/www/itu
        sudo chown -R www-data.www-data /var/www/itu/storage
        sudo chown -R www-data.www-data /var/www/itu/bootstrap/cache
        sudo nano /etc/nginx/sites-available/itu
            paste there:
                server {
                    listen 80;
                    server_name _____server_domain_or_IP_____;
                    root /var/www/itu/public;

                    add_header X-Frame-Options "SAMEORIGIN";
                    add_header X-XSS-Protection "1; mode=block";
                    add_header X-Content-Type-Options "nosniff";

                    index index.html index.htm index.php;

                    charset utf-8;

                    location / {
                        try_files $uri $uri/ /index.php?$query_string;
                    }

                    location = /favicon.ico { access_log off; log_not_found off; }
                    location = /robots.txt  { access_log off; log_not_found off; }

                    error_page 404 /index.php;

                    location ~ \.php$ {
                        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
                        fastcgi_index index.php;
                        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
                        include fastcgi_params;
                    }

                    location ~ /\.(?!well-known).* {
                        deny all;
                    }
                }

        sudo ln -s /etc/nginx/sites-available/itu /etc/nginx/sites-enabled/
        sudo nginx -t
            if Output == folowed => ok
                nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
                nginx: configuration file /etc/nginx/nginx.conf test is successful

        sudo systemctl reload nginx

        # dokončení - nastavení databáze
        .env
            .env file
                DB_CONNECTION=mysql
                DB_HOST=localhost
                DB_PORT=3306
                DB_DATABASE=db
                DB_USERNAME=jan
                DB_PASSWORD=password

        php artisan migrate:fresh --seed

        => http://_____server_domain_or_IP_____


    production =>
        .env
            APP_ENV=production
            php artisan route:cache

    errors z ngixt jsou v:
        cat /var/log/nginx/error.log

server {

    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

    root /var/www/pipedrive/public;
    index index.php index.html index.htm;
    charset utf-8;

    server_name localhost;
    

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico {
        access_log off;
        log_not_found off; 
    }
    location = /robots.txt  {
        access_log off;
        log_not_found off;
    }
    
    access_log off;
    error_log  /var/log/nginx/server-error.log error;

    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # auth_basic "Enter username and password";
    # auth_basic_user_file /etc/nginx/.htpasswd;

}

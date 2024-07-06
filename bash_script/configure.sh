#!/bin/bash

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

configure_nginx() {
  echo -e "${YELLOW}Configuring nginx for XDashboard...${NC}"

  sudo tee -a /etc/nginx/sites-available/default <<'EOF'

server {
    listen 80;
    server_name example.com;  # Replace with your server name
    root /var/www/html/xd;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;  # Adjust PHP version if necessary
        fastcgi_param PHP_VALUE "memory_limit=4096M";
    }

    location ~ /\.ht {
        deny all;
    }

    location /ws {
        proxy_pass http://127.0.0.1:8880/;
        proxy_redirect off;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_read_timeout 52w;
    }

    location /drp {
        proxy_pass http://127.0.0.1:9990/;
        proxy_redirect off;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_read_timeout 52w;
    }
}
EOF

  sudo nginx -t && sudo systemctl reload nginx

  echo -e "${GREEN}nginx configured successfully for XDashboard.${NC}"
}

create_htaccess() {
  echo -e "${YELLOW}Creating .htaccess file for XDashboard...${NC}"

  cat <<'EOF' > /var/www/html/xd/.htaccess
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^([^/]+)$ index.php?url=$1 [L,QSA]
</IfModule>

# Deny access to .htaccess file itself
<Files .htaccess>
  Order Allow,Deny
  Deny from all
</Files>

# Deny access to other sensitive files
<FilesMatch "\.(env|json|ini|log|sh|sql|bak)$">
  Order Allow,Deny
  Deny from all
</FilesMatch>
EOF

  echo -e "${GREEN}.htaccess file created successfully.${NC}"
}

configure_nginx
create_htaccess

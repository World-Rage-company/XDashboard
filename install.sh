#!/bin/bash

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

REPO_URL="https://github.com/World-Rage-company/XDashboard"
INSTALL_DIR="/var/www/html/xd"
TEMP_DIR="/tmp/XDashboard"
DB_NAME="XPanel_plus"

if [ "$EUID" -ne 0 ]; then
  echo -e "${RED}Please run this script as root.${NC}"
  exit 1
fi

check_os_version() {
  if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS=$NAME
    VERSION=$VERSION_ID
    if [[ "$OS" == "Ubuntu" && $(echo "$VERSION >= 20.04" | bc -l) -eq 1 ]]; then
      echo -e "${GREEN}Operating system and Ubuntu version are suitable.${NC}"
    else
      echo -e "${RED}This script only supports Ubuntu 20.04 and above.${NC}"
      exit 1
    fi
  else
    echo -e "${RED}Cannot detect the operating system.${NC}"
    exit 1
  fi
}

check_xpanel_installed() {
  if [ -d "/var/www/html/cp" ]; then
    echo -e "${GREEN}XPanel installation directory found.${NC}"
  else
    echo -e "${RED}XPanel installation directory not found. Please install XPanel and try again.${NC}"
    exit 1
  fi
}

configure_needrestart() {
  echo -e "${YELLOW}Configuring needrestart...${NC}"
  apt-get install -y needrestart
  if [ $? -eq 0 ]; then
    echo -e "${GREEN}needrestart configured successfully.${NC}"
  else
    echo -e "${RED}Error configuring needrestart.${NC}"
    exit 1
  fi
}

install_xdashboard() {
  echo -e "${YELLOW}Creating installation directory...${NC}"
  mkdir -p "$INSTALL_DIR"
  if [ $? -ne 0 ]; then
    echo -e "${RED}Failed to create installation directory.${NC}"
    exit 1
  fi
  echo -e "${YELLOW}Cloning the repository...${NC}"
  git clone "$REPO_URL" "$TEMP_DIR"
  if [ $? -ne 0 ]; then
    echo -e "${RED}Failed to clone the repository.${NC}"
    exit 1
  fi
  echo -e "${YELLOW}Copying new files...${NC}"
  cp -r "$TEMP_DIR/Web_XDashboard/"* "$INSTALL_DIR"
  if [ $? -eq 0 ]; then
    echo -e "${GREEN}XDashboard installed successfully.${NC}"
  else
    echo -e "${RED}Failed to copy new files.${NC}"
    exit 1
  fi
  rm -rf "$TEMP_DIR"
}

configure_database() {
  read -p "Enter XPanel username: " db_user
  read -sp "Enter XPanel password: " db_pass
  echo
  mysql -u"$db_user" -p"$db_pass" -e "exit"
  if [ $? -ne 0 ]; then
    echo -e "${RED}MySQL connection failed. Please check your credentials.${NC}"
    exit 1
  fi
  sed -i "s/define('DB_USER', '');/define('DB_USER', '$db_user');/" "$INSTALL_DIR/assets/php/database/config.php"
  sed -i "s/define('DB_PASS', '');/define('DB_PASS', '$db_pass');/" "$INSTALL_DIR/assets/php/database/config.php"
  echo -e "${GREEN}Database configured successfully.${NC}"

  column_exists=$(mysql -u"$db_user" -p"$db_pass" -D"$DB_NAME" -Bse "SHOW COLUMNS FROM users LIKE 'access'")
  if [ -z "$column_exists" ]; then
    mysql -u"$db_user" -p"$db_pass" -D"$DB_NAME" -e "ALTER TABLE users ADD access BOOLEAN DEFAULT TRUE;"
    if [ $? -eq 0 ]; then
      echo -e "${GREEN}Access column added successfully.${NC}"
    else
      echo -e "${RED}Failed to add access column.${NC}"
      exit 1
    fi
  else
    echo -e "${YELLOW}Access column already exists.${NC}"
  fi

  table_exists=$(mysql -u"$db_user" -p"$db_pass" -D"$DB_NAME" -Bse "SHOW TABLES LIKE 'tickets'")
  if [ -z "$table_exists" ]; then
    mysql -u"$db_user" -p"$db_pass" -D"$DB_NAME" -e "
    CREATE TABLE tickets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        admin_id BIGINT UNSIGNED DEFAULT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        status ENUM('open', 'in progress', 'closed') DEFAULT 'open',
        priority ENUM('high', 'medium', 'low') DEFAULT 'medium',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (admin_id) REFERENCES admins(id)
    );"
    if [ $? -eq 0 ]; then
      echo -e "${GREEN}Table 'tickets' created successfully.${NC}"
    else
      echo -e "${RED}Failed to create table 'tickets'.${NC}"
      exit 1
    fi
  else
    echo -e "${YELLOW}Table 'tickets' already exists.${NC}"
  fi

  table_exists=$(mysql -u"$db_user" -p"$db_pass" -D"$DB_NAME" -Bse "SHOW TABLES LIKE 'ticket_responses'")
  if [ -z "$table_exists" ]; then
    mysql -u"$db_user" -p"$db_pass" -D"$DB_NAME" -e "
    CREATE TABLE ticket_responses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ticket_id INT NOT NULL,
        responder_id BIGINT UNSIGNED NOT NULL,
        response TEXT NOT NULL,
        responded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (ticket_id) REFERENCES tickets(id),
        FOREIGN KEY (responder_id) REFERENCES admins(id)
    );"
    if [ $? -eq 0 ]; then
      echo -e "${GREEN}Table 'ticket_responses' created successfully.${NC}"
    else
      echo -e "${RED}Failed to create table 'ticket_responses'.${NC}"
      exit 1
    fi
  else
    echo -e "${YELLOW}Table 'ticket_responses' already exists.${NC}"
  fi
}

add_nginx_config() {
  local default_nginx_config="/etc/nginx/sites-available/default"
  local server_block
  local existing_port
  if [ -d "/var/www/html/xd" ]; then
    echo -e "${YELLOW}Directory /var/www/html/xd already exists. Checking existing nginx configuration.${NC}"
    server_block=$(awk -v RS="" "/root $INSTALL_DIR;/ {print; exit}" "$default_nginx_config")
    if [ -n "$server_block" ]; then
      existing_port=$(echo "$server_block" | grep -oP '(?<=listen )\d+')
      echo -e "${GREEN}Found existing server block with port: $existing_port${NC}"
      port=$existing_port
      return
    else
      echo -e "${RED}No existing server block found for $INSTALL_DIR. Adding new configuration.${NC}"
    fi
  fi
  read -p "Enter the port number for the new nginx server (leave blank for random): " port
  if [ -z "$port" ]; then
    port=$(( ( RANDOM % 1000 )  + 9000 ))
  fi
  read -p "Do you want to use SSL for this server? (y/n): " use_ssl
  if [[ "$use_ssl" =~ ^[Yy]$ ]]; then
    read -p "Enter your domain name (e.g., example.com): " domain
    certbot --nginx -d "$domain" -d "www.$domain"
    nginx_config="
    server {
        listen $port ssl;
        server_name $domain www.$domain;
        root $INSTALL_DIR;
        index index.php index.html;

        ssl_certificate /etc/letsencrypt/live/$domain/fullchain.pem;
        ssl_certificate_key /etc/letsencrypt/live/$domain/privkey.pem;

        location / {
            try_files \$uri \$uri/ /index.php?\$query_string;
        }

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
            fastcgi_param PHP_VALUE \"memory_limit=4096M\";
        }

        location ~ /\.ht {
            deny all;
        }
    }
    "
  else
    if hostname -I > /dev/null 2>&1; then
      server_ip=$(hostname -I | awk '{print $1}')
      domain=$server_ip
    else
      domain=$(hostname)
    fi
    echo -e "${YELLOW}No SSL configuration found. Using IP address as server name.${NC}"
    nginx_config="
    server {
        listen $port;
        server_name $domain;
        root $INSTALL_DIR;
        index index.php index.html;

        location / {
            try_files \$uri \$uri/ /index.php?\$query_string;
        }

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
            fastcgi_param PHP_VALUE \"memory_limit=4096M\";
        }

        location ~ /\.ht {
            deny all;
        }
    }
    "
  fi
  echo "$nginx_config" | sudo tee -a "$default_nginx_config" > /dev/null
  echo -e "${GREEN}Nginx configuration added successfully.${NC}"
}

progress_bar() {
  local duration=${1:-10}
  local interval=1
  local count=$((duration / interval))
  local progress_char="▓"
  local track_char="░"
  echo -n "Progress: "
  for ((i = 0; i < count; i++)); do
    echo -ne "${progress_char}"
    sleep $interval
  done
  echo -e " ${GREEN}Complete${NC}"
}

endINSTALL() {
  if [[ "$use_ssl" =~ ^[Yy]$ ]]; then
    protoco="https"
  else
    protoco="http"
  fi

  echo -e "************ XDashboard ************ \n"
  echo -e "XCPL Link : $protoco://${domain}:$port/xcpl"
  echo -e "XDashboard Link : $protoco://${domain}:$port"
}

check_os_version
check_xpanel_installed
configure_needrestart

echo -e "${YELLOW}Installing XDashboard...${NC}"

install_xdashboard
progress_bar
configure_database
add_nginx_config

systemctl restart nginx

endINSTALL

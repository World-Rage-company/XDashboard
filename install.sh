#!/bin/bash

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

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
  if command -v xpanel &> /dev/null; then
    echo -e "${GREEN}Xpanel is installed.${NC}"
  else
    echo -e "${RED}Xpanel installation error. Please install Xpanel and try again.${NC}"
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

get_user_input() {
  while true; do
    read -p "Please enter Xpanel username: " username
    read -sp "Please enter Xpanel password: " password
    echo ""

    # Test MySQL connection
    if mysql -u"$username" -p"$password" -e "exit" 2>/dev/null; then
      echo -e "${GREEN}Successfully connected to the database.${NC}"
      break
    else
      echo -e "${RED}Invalid username or password. Please try again.${NC}"
    fi
  done
}

download_and_copy_files() {
  echo -e "${YELLOW}Downloading Web_XDashboard contents...${NC}"
  wget -q https://github.com/World-Rage-company/XDashboard/archive/refs/heads/master.zip -O /tmp/Web_XDashboard.zip
  if [ $? -ne 0 ]; then
    echo -e "${RED}Error downloading Web_XDashboard contents.${NC}"
    exit 1
  fi

  echo -e "${YELLOW}Extracting Web_XDashboard contents...${NC}"
  unzip -q /tmp/Web_XDashboard.zip -d /tmp
  if [ $? -ne 0 ]; then
    echo -e "${RED}Error extracting Web_XDashboard contents.${NC}"
    exit 1
  fi

  echo -e "${YELLOW}Copying Web_XDashboard contents...${NC}"
  cp -r /tmp/XDashboard-master/Web_XDashboard/* /var/www/html/example/
  if [ $? -ne 0 ]; then
    echo -e "${RED}Error copying Web_XDashboard contents.${NC}"
    exit 1
  fi
}

configure_database() {
  echo -e "${YELLOW}Configuring database...${NC}"
  mysql_config_editor set --login-path=xpanel --host=localhost --user="$username" --password="$password"

  sed -i "s/define('DB_USER', 'username');/define('DB_USER', '$username');/" /var/www/html/example/assets/php/database/config.php
  sed -i "s/define('DB_PASS', 'password');/define('DB_PASS', '$password');/" /var/www/html/example/assets/php/database/config.php
  if [ $? -eq 0 ]; then
    echo -e "${GREEN}Database configured successfully.${NC}"
  else
    echo -e "${RED}Error configuring database.${NC}"
    exit 1
  fi
}

# Main script flow
check_os_version
check_xpanel_installed
configure_needrestart
get_user_input
download_and_copy_files
configure_database

echo -e "${GREEN}Installation completed successfully.${NC}"

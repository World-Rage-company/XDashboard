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

    mysql -u"$username" -p"$password" -e "exit" 2>/dev/null
    if [ $? -eq 0 ]; then
      echo -e "${GREEN}Successfully connected to the database.${NC}"
      break
    else
      echo -e "${RED}Invalid username or password. Please try again.${NC}"
    fi
  done
}

configure_xdashboard() {
  local username="$1"
  local password="$2"

  echo -e "${YELLOW}Copying Web_XDashboard contents...${NC}"
  cp -r Web_XDashboard/* /var/www/html/example/
  if [ $? -eq 0 ]; then
    echo -e "${GREEN}Web_XDashboard contents copied successfully.${NC}"
  else
    echo -e "${RED}Error copying Web_XDashboard contents.${NC}"
    exit 1
  fi

  local config_file="/var/www/html/example/assets/php/database/config.php"
  echo -e "${YELLOW}Configuring database settings...${NC}"
  sed -i "s/define('DB_USER', 'username');/define('DB_USER', '$username');/" "$config_file"
  sed -i "s/define('DB_PASS', 'password');/define('DB_PASS', '$password');/" "$config_file"
  if [ $? -eq 0 ]; then
    echo -e "${GREEN}Database settings configured successfully.${NC}"
  else
    echo -e "${RED}Error configuring database settings.${NC}"
    exit 1
  fi
}

check_os_version
check_xpanel_installed
configure_needrestart
get_user_input
username="$username"
password="$password"
configure_xdashboard "$username" "$password"

echo -e "${GREEN}Installation completed successfully.${NC}"

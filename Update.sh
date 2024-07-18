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

check_xdashboard_installed() {
  if [ -d "$INSTALL_DIR" ]; then
    echo -e "${GREEN}XDashboard installation directory found.${NC}"
  else
    echo -e "${RED}XDashboard installation directory not found. Please install XDashboard first.${NC}"
    exit 1
  fi
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
}

update_xdashboard() {
  echo -e "${YELLOW}Removing existing files in installation directory...${NC}"
  rm -rf "$INSTALL_DIR"/*
  if [ $? -ne 0 ]; then
    echo -e "${RED}Failed to remove existing files.${NC}"
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
    echo -e "${GREEN}XDashboard updated successfully.${NC}"
  else
    echo -e "${RED}Failed to copy new files.${NC}"
    exit 1
  fi
  rm -rf "$TEMP_DIR"
}

check_os_version
check_xpanel_installed
check_xdashboard_installed

echo -e "${YELLOW}Updating XDashboard...${NC}"
update_xdashboard
configure_database

systemctl restart nginx

echo -e "${GREEN}XDashboard has been updated successfully.${NC}"

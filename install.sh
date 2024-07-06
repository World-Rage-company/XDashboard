#!/bin/bash

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

REPO_URL="https://github.com/World-Rage-company/XDashboard"
INSTALL_DIR="/var/www/html/xd"
TEMP_DIR="/tmp/XDashboard"
CONFIGURE_SCRIPT="bash_script/configure.sh"

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

check_xdashboard_installed() {
  if [ -d "$INSTALL_DIR" ]; then
    echo -e "${YELLOW}Existing installation found. Removing old files...${NC}"
    rm -rf "$INSTALL_DIR"
    if [ $? -ne 0 ]; then
      echo -e "${RED}Failed to remove old installation.${NC}"
      exit 1
    fi
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

configure_application() {
  echo -e "${YELLOW}Running configuration script...${NC}"
  bash "$INSTALL_DIR/$CONFIGURE_SCRIPT"
}

configure_database() {
  read -p "Enter MySQL username: " db_user
  read -sp "Enter MySQL password: " db_pass
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

check_os_version
check_xpanel_installed
check_xdashboard_installed
configure_needrestart
install_xdashboard
configure_application
configure_database

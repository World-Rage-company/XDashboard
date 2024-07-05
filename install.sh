#!/bin/bash

X_DASHBOARD_DIR="/var/www/html/example/XDashboard"
WEB_XDASHBOARD_DIR="Web_XDashboard"
INDEX_PAGE_URL="ip/xdashboard/index.php"

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

check_root() {
    if [ "$EUID" -ne 0 ]; then
        echo -e "${RED}Please run this script as root.${NC}"
        exit 1
    fi
}

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

copy_web_xdashboard() {
    echo "Copying Web_XDashboard contents to $X_DASHBOARD_DIR..."
    if [ ! -d "$X_DASHBOARD_DIR" ]; then
        mkdir -p "$X_DASHBOARD_DIR"
    else
        rm -rf "${X_DASHBOARD_DIR:?}/"*
    fi

    cp -r "$WEB_XDASHBOARD_DIR"/* "$X_DASHBOARD_DIR/"
    if [ $? -eq 0 ]; then
        echo "Web_XDashboard contents copied successfully."
    else
        echo "Error copying Web_XDashboard contents."
        exit 1
    fi
}

provide_index_url() {
    echo ""
    echo -e "You can access XDashboard at: ${YELLOW}http://$INDEX_PAGE_URL${NC}"
}

check_root
check_os_version
check_xpanel_installed
configure_needrestart
get_user_input
copy_web_xdashboard
provide_index_url

echo -e "${YELLOW}End of script: Web_XDashboard installed in $X_DASHBOARD_DIR.${NC}"

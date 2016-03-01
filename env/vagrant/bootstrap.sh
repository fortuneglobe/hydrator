#!/usr/bin/env bash

# set correct permissions for private key
chmod 0700 /root/.ssh
chmod 0600 /root/.ssh/id_rsa
chmod 0600 /root/.ssh/config

# restart php5-fpm
service php5-fpm restart

# Determine the public ip address and show a message
IP_ADDR=`ifconfig eth1 | grep inet | grep -v inet6 | awk '{print $2}' | cut -c 6-`

echo -e "\e[0m--\nYour IP address is: \e[1;31m$IP_ADDR\e[0m\n--\n"



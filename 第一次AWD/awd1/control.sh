#!/bin/bash

action=$1
if [ "$action" = "down" ]; then
    docker-compose -f ./run/docker-compose.yml down
    docker rmi -f $(docker images|grep 'awd1'|awk '{print $3}')
    rm -rf ./run/team*
elif [ "$action" = "stop" ]; then
    docker-compose -f ./run/docker-compose.yml stop
elif [ "$action" = "restart" ]; then
    docker-compose -f ./run/docker-compose.yml restart
else
    echo "sh control.sh stop/restart/down"
fi

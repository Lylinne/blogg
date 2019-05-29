#!/bin/bash

docker-compose stop
sleep 3;
docker-compose rm -f

echo
echo "#---------------------------------------------"
echo "#"
echo "#"
echo "# Supression ok"
echo "#"
echo "#"
echo "#----------------------------------------------"

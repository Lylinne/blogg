#!/bin/bash

docker-compose build

docker-compose -f docker-compose.yml up -docker

echo
echo "#----------------------------------------------------"
echo "#"
echo "# Please check your browser to see if it is "
echo "#"
echo "#-----------------------------------------------------"

exit 0
# Deploy IMS API + Cli using git
# Call syntax : ./deploy.sh

#!/usr/bin/env bash

echo "Going to deploy IMS on Yuki"

# Going to folder on Yuki
cd /var/www/clients/leonardzone/public/miageStage

# Stash every prod related changes
git stash

# Pull from master
git pull origin master

## Pop stashed changes
git stash pop

echo "Api deployed"

## Client tasks
cd cli/prod

## Deleting dependencies
rm -rf bower_components

## Install everything
bower install

echo "Client deployed, dependencies updated"
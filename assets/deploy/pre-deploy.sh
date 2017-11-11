## Preparing deploy
## Call syntax : ./pre-deploy.sh releaseName
#!/usr/bin/env bash

echo "Preparing deploy"

# Going down
cd ..

# Stash any changes
git stash

# Checkout master
git checkout master

# Tag new release
git tag -a $1 -m "$1"

# Push new tags
git push --tags

# Building using polymer-cli
polymer build

# Deleting current prod release
rm -rf prod

# Copy builded to prod
cp build/es5-bundled prod/

# Copy bower.json
cp -rf bower.json prod/bower.json

echo "Ready to deploy!"
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

# Building using polymer-cli
polymer build

# Deleting current prod release
rm -rf prod

# Copy builded to prod
cp build/es5-bundled prod/

# Copy bower.json
cp -rf bower.json prod/bower.json

# Add everything
git add .

# Commit new release
git commit -m "Pre-deploy new release $1"

# Tag new release
git tag -a $1 -m "Release: $1"

# Push.
git push

echo "Ready to deploy!"
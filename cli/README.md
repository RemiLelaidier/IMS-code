# IMS - Internship Management System
#### Made by Miagists for Miagists

Based on [Polymer Starter Kit](https://github.com/PolymerElements/polymer-starter-kit)
| Polymer 2.0

### Todo

- Need to do an admin panel
- Need to build a strong auth
- Polymer front need to make calls to an API when convention ceremony is done.
- We need a database (MongoDB ?)
- We also need to make an API using (Slim3?) and JWT to authenticate
- Check / add validation on convention ceremony
- Add localization ? Specs ?

## Working demo
[IMS Stage](http://beta.leonard.zone)

### Setup

##### Prerequisites

First, install [Polymer CLI](https://github.com/Polymer/polymer-cli) using
[npm](https://www.npmjs.com) (we assume you have pre-installed [node.js](https://nodejs.org)).

    npm install -g polymer-cli

Second, install [Bower](https://bower.io/) using [npm](https://www.npmjs.com)

    npm install -g bower

### Start the development server

If it's the first time, you need to install required components :

    bower install


This command serves the app at `http://127.0.0.1:8081` and provides basic URL
routing for the app:

    polymer serve

**Pro-tip :** To open browser in the same time :

    polymer serve -o


### Build

The `polymer build` command builds the IMS-App in several versions

- bundled -> optimized
- unbundled -> for debug

(assuming you know what is ES6, if not, it's the next version of Javascript, go on [ExploringJS](http://exploringjs.com/es6/))

ES5-bundled version should be used to avoid any problem with old / mobile browsers on prod.

### Preview the build

This command serves your app. Replace `build-folder-name` with the folder name of the build you want to serve.

    polymer serve build/build-folder-name/

### Run tests

This command will run [Web Component Tester](https://github.com/Polymer/web-component-tester)
against the browsers currently installed on your machine:

    polymer test

If running Windows you will need to set the following environment variables:

- LAUNCHPAD_BROWSERS
- LAUNCHPAD_CHROME

Read More here [daffl/launchpad](https://github.com/daffl/launchpad#environment-variables-impacting-local-browsers-detection)

History
--------------------
* 10/11/2017 : PSK -> IMS Readme by Léonard

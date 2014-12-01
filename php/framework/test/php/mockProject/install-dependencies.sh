#!/bin/sh

cd "$(dirname ${BASH_SOURCE[0]})"

#bower install --config.interactive=false
npm install
grunt bower

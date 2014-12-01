#!/bin/sh

realpath () {
  [[ $1 = /* ]] && echo "$1" || echo "$PWD/${1#./}"
}

cd $(dirname `readlink -f $0 || realpath $0`)

#bower install --config.interactive=false
npm install
grunt bower

#!/bin/bash

docker run -it --rm --init --network workspace --user 1000 -w /var/www -v ${PWD}:/var/www node:14 $@

#!/bin/bash

if [ $# -ne 2 ] ; then
    echo "Source and destination arguments are required"
    exit 1
fi
scp -i $(dirname $0)/../dev1.pem ubuntu@repodev1.malov.net:"$1"  "$2"
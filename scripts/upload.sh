#!/bin/bash

# upload php sources
#scp -r -i $(dirname $0)/../dev1.pem  $(dirname $0)/../wroot/*  ubuntu@repodev1.malov.net:/home/ubuntu/bluesite
rsync -avz -e "ssh -i $(dirname $0)/../dev1.pem" $(dirname $0)/../wroot/  ubuntu@repodev1.malov.net:/home/ubuntu/bluesite/wroot/
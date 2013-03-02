#!/bin/bash

# create an archive for deployment

git archive --format=tar HEAD | gzip -c > $(dirname $(dirname $0))/bluesite-$(date  +"%Y-%0m-%d").tar.gz
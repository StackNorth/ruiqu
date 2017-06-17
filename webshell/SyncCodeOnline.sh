#!/bin/sh
DEPLOY_DIR=/data/yicheng
if [ ! -d $DEPLOY_DIR ] ; then
echo >&2 "fatal: post-receive: DEPLOY_DIR_NOT_EXIST: \"$DEPLOY_DIR\""
exit 1
fi
# Goto the deploy dir and pull the latest sources
cd $DEPLOY_DIR
#env -i git reset --hard
env -i git pull origin master
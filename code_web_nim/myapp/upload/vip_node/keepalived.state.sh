#!/bin/bash
links_script_notify=$0
TYPE=$1
NAME=$2
STATE=$3
Priority=$4

echo $STATE > /root/keepalived.state

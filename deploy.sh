#!/bin/sh

for file in $@; do
  scp $file bakineggs:/var/www/bakineggs.com/httpdocs/$file
done

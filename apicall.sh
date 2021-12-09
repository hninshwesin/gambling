#!/bin/bash

while true; do
  cd /home/shwesin/code/gambling && php artisan schedule:run
  sleep 1;
done

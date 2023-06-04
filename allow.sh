#!/bin/bash
sudo chown -R www-data:www-data *
sudo setfacl -R -m u:mercatavico:wrx .

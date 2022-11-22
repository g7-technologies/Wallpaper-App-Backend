#!/bin/bash

kill -9 $(ps asx | grep 'receipts:validate_renewing' | grep '/rap.wallpapers.wiki' | awk '{print $2}')
php /var/www/vhosts/choiceathome.net/httpdocs/artisan receipts:validate_renewing

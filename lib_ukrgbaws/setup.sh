#!/bin/bash
composer="../composer.phar"
top=$PWD

if [ ! -e ${composer} ]
then
    cd ..
    curl -sS https://getcomposer.org/installer | php
fi
cd $top
php ../composer.phar require aws/aws-sdk-php

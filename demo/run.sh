#!/bin/bash

if [ ! -d ZendSkeletonApplication ]
then
    git clone https://github.com/zendframework/ZendSkeletonApplication.git;
    cd ZendSkeletonApplication;
else
    cd ZendSkeletonApplication;
    git reset --hard origin/master;
fi;

composer require zf-commons/zfc-user:1.*@stable
composer install --no-dev;

cp ../files/application.config.php.dist config/application.config.php;
cp ../files/*.local.php ../files/*.global.php config/autoload;

rm data/users.db 2>/dev/null
cat vendor/zf-commons/zfc-user/data/schema.sqlite.sql | sqlite3 data/users.db 

cd module;
rm ExtensionModule 2>/dev/null;
ln -s ../../ExtensionModule;
rm LdcUserProfile 2>/dev/null;
ln -s ../../../ LdcUserProfile;
cd - >/dev/null;

php -S 0.0.0.0:8080 -t public


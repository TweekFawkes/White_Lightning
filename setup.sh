#!/bin/bash

# Configure Script
WL_DOMAIN='example.com'
EL_DOMAIN='blog.example.com'
MSGRPC_IP='192.168.187.101'

PASS_MYSQL_ROOT='P@ssw0rd!1'

USERNAME_MYSQL_WEBAPP='wlWebApp'
PASS_MYSQL_WEBAPP='P@ssw0rd!2'

USERNAME_WEBAPP_ADMIN='admin'
PASS_WEBAPP_ADMIN='P@ssw0rd!3'

USERNAME_WEBAPP_USER='user'
PASS_WEBAPP_USER='P@ssw0rd!4'

PASS_MSGRPC='abc123'


### ### ### !!! DO NOT EDIT BELOW HERE !!! ### ### ###

# Configure Script - You shouldn't need to edit any of these
OLD_WL_DOMAIN='example.io'
OLD_EL_DOMAIN='blog.example.io'
OLD_MSGRPC_IP='192.168.187.101'

OLD_PASS_MYSQL_ROOT='P@ssw0rd!mysqlroot'

OLD_USERNAME_MYSQL_WEBAPP='hobbyhorse'
OLD_PASS_MYSQL_WEBAPP='P@ssw0rd!mysqlwebapp'

OLD_USERNAME_WEBAPP_ADMIN='gator'
OLD_PASS_WEBAPP_ADMIN='P@ssw0rd!webappadmin'

OLD_USERNAME_WEBAPP_USER='bear'
OLD_PASS_WEBAPP_USER='P@ssw0rd!webappuser'

OLD_PASS_MSGRPC='abc123'


# Install needed software
apt-get -y install php5-dev php-pear build-essential
pecl install channel://pecl.php.net/msgpack-0.5.5
echo "extension=msgpack.so" >> /etc/php5/apache2/php.ini

apt-get -y install curl libcurl3 libcurl3-dev php5-curl


# Start Services on Startup
update-rc.d postgresql enable
service postgresql start

update-rc.d metasploit enable
service metasploit start

update-rc.d apache2 enable
service apache2 restart

update-rc.d mysql enable
service mysql restart


# Replace Function
function replace() {
	declare -a files
	readarray -t files < <(grep -rl "$1" ./* | egrep -v "README.md|setup.sh")
	for i in "${files[@]}"
	do
		TMP=`sed "s/${1}/${2}/g" "$i"`
		echo -n "$TMP" > "$i"
		echo "$i --  $1 changed to $2"
	done
}


# Replace hard-coded passwords
replace $OLD_EL_DOMAIN $EL_DOMAIN
replace $OLD_WL_DOMAIN $WL_DOMAIN
replace $OLD_MSGRPC_IP $MSGRPC_IP

replace $OLD_PASS_MYSQL_ROOT $PASS_MYSQL_ROOT

replace $OLD_USERNAME_MYSQL_WEBAPP $USERNAME_MYSQL_WEBAPP

replace $OLD_PASS_MYSQL_WEBAPP $PASS_MYSQL_WEBAPP

replace $OLD_USERNAME_WEBAPP_ADMIN $USERNAME_WEBAPP_ADMIN

replace $OLD_PASS_WEBAPP_ADMIN $PASS_WEBAPP_ADMIN

replace $OLD_USERNAME_WEBAPP_USER $USERNAME_WEBAPP_USER

replace $OLD_PASS_WEBAPP_USER $PASS_WEBAPP_USER

replace $OLD_PASS_MSGRPC $PASS_MSGRPC

#replace 'P@ssw0rd!' $PASS
#replace 'passwww' $PASS
#replace 'gator' 'admin'
#replace 'bear' 'user'


echo "######################"
echo
#echo "moving etc/apache2/sites-available/example.io to etc/apache2/sites-available/${WL_DOMAIN}"
#mv ./apacheReverseProxy/sites-available/example.io "./apacheReverseProxy/sites-available/${WL_DOMAIN}"
#echo "moving etc/apache2/sites-available/blog.example.io to etc/apache2/sites-available/${EL_DOMAIN}"
#mv ./apacheReverseProxy/sites-available/blog.example.io "./apacheReverseProxy/sites-available/${EL_DOMAIN}"
echo

# these might need testing...
echo "copying WL files to system root..."
cp -r ./whiteLightning/* /var/
cp -r ./wlMsgrpc/* /root/
cp -r ./apacheReverseProxy/* /etc/apache2/

echo
echo "modifying Apache configuration..."

ln -s /etc/apache2/mods-available/proxy.load /etc/apache2/mods-enabled
ln -s /etc/apache2/mods-available/proxy_http.load /etc/apache2/mods-enabled
mv /etc/apache2/sites-available/${OLD_WL_DOMAIN} /etc/apache2/sites-available/${WL_DOMAIN}
mv /etc/apache2/sites-available/www.${OLD_WL_DOMAIN} /etc/apache2/sites-available/www.${WL_DOMAIN}
mv /etc/apache2/sites-available/${OLD_EL_DOMAIN} /etc/apache2/sites-available/${EL_DOMAIN}
ln -s /etc/apache2/sites-available/${WL_DOMAIN} /etc/apache2/sites-enabled/001-${WL_DOMAIN}
ln -s /etc/apache2/sites-available/${EL_DOMAIN} /etc/apache2/sites-enabled/002-${EL_DOMAIN}

chown -R www-data:www-data /var/www
chmod -R g+rw /var/www

#TMP=`sed 's/#*NameVirtualHost \*:80/NameVirtualHost \*/g' /etc/apache2/ports.conf`
#echo "$TMP" > /etc/apache2/ports.conf

TMP=`sed 's/^DefaultType .*$/DefaultType application\/x-httpd-php/g' /etc/apache2/apache2.conf`
echo "$TMP" > /etc/apache2/apache2.conf

echo "ServerName ${WL_DOMAIN}" >> /etc/apache2/apache2.conf

echo
echo "setting up mysql..."
echo
echo "This run will set your password.  Hit enter if you haven't changed it before now."
mysql -f -u root -p < ./all.sql
#echo "Password changed.  Now use the new one.  This run will create a new user for WL."
#mysql -f -u root -p < ./create.sql
#echo "User should now be created.  Next run will create tables for the new user."
#mysql -f -u hobbyhorse -p < ./tables.sql
echo "Should be all done.  If you got errors, try re-running the SQL in the WL directory."

touch /var/www/e/debug.log
chmod 777 //var/www/e/debug.log

# restart services!!! :)
echo
service apache2 restart
service mysql restart
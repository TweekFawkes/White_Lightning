#!/bin/bash

apt-get install php5-dev php-pear build-essential
pecl install channel://pecl.php.net/msgpack-0.5.5
echo "extension=msgpack.so" >> /etc/php5/apache2/php.ini
apt-get install curl libcurl3 libcurl3-dev php5-curl
update-rc.d postgresql enable
service postgresql start
update-rc.d metasploit enable
service metasploit start
update-rc.d apache2 enable
service apache2 restart
update-rc.d mysql enable
service mysql restart

OLD_WL_DOMAIN='qu.gs'
OLD_EL_DOMAIN='blog.qu.gs'
OLD_MSGRPC_IP='10.191.53.90'
OLD_PASS='mysecretpassword'

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

echo "######################"
echo "User configuration"
echo "######################"
echo
echo -n "Enter new White Lighning domain: "
read WL_DOMAIN

echo -n "Enter new exploit domain: "
read EL_DOMAIN

echo -n "Enter new MSGRPC IP address: "
read MSGRPC_IP

echo -n "Enter new mysql password: "
read PASS

replace $OLD_EL_DOMAIN $EL_DOMAIN
replace $OLD_WL_DOMAIN $WL_DOMAIN
replace $OLD_MSGRPC_IP $MSGRPC_IP
replace $OLD_PASS $PASS

echo "######################"
echo
echo "moving etc/apache2/sites-available/qu.gs to etc/apache2/sites-available/${WL_DOMAIN}"
mv ./etc/apache2/sites-available/qu.gs "./etc/apache2/sites-available/${WL_DOMAIN}"
echo "moving etc/apache2/sites-available/blog.qu.gs to etc/apache2/sites-available/${EL_DOMAIN}"
mv ./etc/apache2/sites-available/blog.qu.gs "./etc/apache2/sites-available/${EL_DOMAIN}"
echo
echo "copying WL files to system root..."
cp -r ./var /
cp -r ./root /
cp -r ./etc /

echo
echo "modifying Apache configuration..."

ln -s /etc/apache2/mods-available/proxy.load /etc/apache2/mods-enabled
ln -s /etc/apache2/mods-available/proxy_http.load /etc/apache2/mods-enabled
ln -s /etc/apache2/sites-available/${WL_DOMAIN} /etc/apache2/sites-enabled/001-${WL_DOMAIN}
ln -s /etc/apache2/sites-available/${EL_DOMAIN} /etc/apache2/sites-enabled/002-${EL_DOMAIN}

chown -R www-data:www-data /var/www
chmod -R g+rw /var/www

TMP=`sed 's/#*NameVirtualHost \*:80/NameVirtualHost \*/g' /etc/apache2/ports.conf`
echo "$TMP" > /etc/apache2/ports.conf

TMP=`sed 's/^DefaultType .*$/DefaultType application\/x-httpd-php/g' /etc/apache2/apache2.conf`
echo "$TMP" > /etc/apache2/apache2.conf

echo
echo "setting up mysql..."
echo
echo "This run will set your password.  Hit enter if you haven't changed it before now."
mysql -u root -p < ./pw.sql
echo "Password changed.  Now use the new one.  This run will create a new user for WL."
mysql -u root -p < ./create.sql
echo "User should now be created.  Next run will create tables for the new user."
mysql -u hobbyhorse -p < ./tables.sql
echo "Should be all done.  If you got errors, try re-running the SQL in the WL directory."

touch /var/www/e/debug.log
chmod 777 //var/www/e/debug.log

echo
service apache2 restart
service mysql restart


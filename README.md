##WELCOME##
WhiteLightning is the next generation of MiTM web exploitation.  This tool was 
created for the Red Team, OpSec conscience pen tester, and for future inovators
to show what can happen when you put a little logic into a framework such as this.

We wrote this because we couldn't find anything out there they gave us what we
wanted.  We found downfalls in all similar products so we decided to make our own.
If there are any features that you want and don't see please submit a ticket 
and we will get to it.  Thanks!

##Highlighted Features##
[*] Developed logic to determine the characteristics of the target environment.
[*] Uses reverse proxying to keep all comms on port 80 (configurable)
[*] Chooses best exploits to throw based on logic from the target
[*] Easy to navigate Bootstrap front end
[*] 100%x100% iFrame redirection (really ingenious Bryce)
[*] Custom logging with group level permissions

##Current Progress##
[ ] Working on creating precompiled application package
    -> Convert mysql database passwords to dynamic assignment
    -> Convert hard coded URLs to dynamic
    -> Create first log on page to configure admin
[ ] Expanding target area from Windows 7+ to OSX
[ ] Email system for alerts

##Recent Developments##
[*] Overhauled front end
[*] New exploits added
[*] Administration pages
[*] Ability to remove tasks
[*] Added robots.txt to web root to prevent crawlers from scraping

##Installation##
Setup has been verified working on January 31, 2015 on KaliLinux 1.0.9.

First, copy all directories (etc, root, var) to the root of your KaliLinux, overwritting the originals.

Then update your software as shown below:
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

##Setup MySQL##
mysql -u root -p

use mysql;
update user set password=PASSWORD("mysecretpassword") where User='root';
flush privileges;
quit

mysql -u root -p

CREATE DATABASE WL;
CREATE USER 'hobbyhorse'@'localhost' IDENTIFIED BY 'mysecretpassword';
GRANT ALL ON WL.* TO 'hobbyhorse'@'localhost';
quit

mysql -u hobbyhorse -p

USE WL;
DROP TABLE users;
CREATE TABLE users (
user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(40) NOT NULL,
pass CHAR(40) NOT NULL,
user_level TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
PRIMARY KEY (user_id),
INDEX login (pass)
);

INSERT INTO users (name, pass) VALUES ('gator', SHA1('P@ssw0rd!'));
UPDATE users SET user_level=1 WHERE name='gator';

INSERT INTO users (name, pass) VALUES ('bear', SHA1('P@ssw0rd!'));

DROP TABLE users_invites;
CREATE TABLE users_invites (
user_invite_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
invite_id INT UNSIGNED NOT NULL,
PRIMARY KEY (user_invite_id)
);

DROP TABLE invites;
CREATE TABLE invites (
invite_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
invite VARCHAR(32) NOT NULL,
active TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
PRIMARY KEY (invite_id)
);

DROP TABLE hits;
CREATE TABLE hits (
hit_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
php_date VARCHAR(8) NOT NULL,
php_time VARCHAR(8) NOT NULL,
php_remote_addr VARCHAR(20) NOT NULL,
php_http_referer VARCHAR(2048) NOT NULL,
php_http_user_agent VARCHAR(2048),
ua_os_family VARCHAR(20),
ua_os_version VARCHAR(20),
ua_os_platform VARCHAR(20),
ua_browser_wow64 VARCHAR(20),
ua_browser_name VARCHAR(20),
ua_browser_version VARCHAR(20),
pd_os VARCHAR(20),
pd_br VARCHAR(40),
pd_br_ver VARCHAR(20),
pd_br_ver_full VARCHAR(40),
me_mshtml_build VARCHAR(20),
be_office VARCHAR(20),
pd_reader VARCHAR(20),
pd_flash VARCHAR(20),
pd_java VARCHAR(20),
pd_qt VARCHAR(20),
pd_rp VARCHAR(20),
pd_shock VARCHAR(20),
pd_silver VARCHAR(20),
pd_wmp VARCHAR(20),
pd_vlc VARCHAR(20),
PRIMARY KEY (hit_id)
);


DROP TABLE taskings;
CREATE TABLE taskings (
tasking_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(200),
date VARCHAR(8) NOT NULL,
time VARCHAR(8) NOT NULL,
random_string VARCHAR(200),
throw_count VARCHAR(8),
frontend_url VARCHAR(2048),
backend_url VARCHAR(2048),
iframe_flag VARCHAR(200),
iframe_url VARCHAR(2048),
iframe_title VARCHAR(2048),
iframe_icon_url VARCHAR(2048),
debug_flag VARCHAR(200),
PRIMARY KEY (tasking_id)
);

DROP TABLE throws;
CREATE TABLE throws (
throw_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
hit_id INT UNSIGNED,
php_date VARCHAR(8),
php_time VARCHAR(8),
msf_exploit_full_path VARCHAR(2048),
msf_target VARCHAR(8),
PRIMARY KEY (throw_id)
);

DROP TABLE loads;
CREATE TABLE loads (
load_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
throw_id INT UNSIGNED,
php_date VARCHAR(8),
php_time VARCHAR(8),
php_remote_addr VARCHAR(20),
php_http_referer VARCHAR(2048),
php_http_user_agent VARCHAR(2048),
PRIMARY KEY (load_id)
);

show tables;

##Apache Modifications##

ln -s /etc/apache2/mods-available/proxy.load /etc/apache2/mods-enabled
ln -s /etc/apache2/mods-available/proxy_http.load /etc/apache2/mods-enabled
ln -s /etc/apache2/sites-available/qu.gs /etc/apache2/sites-enabled/001-qu.gs
ln -s /etc/apache2/sites-available/www.qu.gs /etc/apache2/sites-enabled/002-www.qu.gs
ln -s /etc/apache2/sites-available/blog.qu.gs /etc/apache2/sites-enabled/003-blog.qu.gs

vi /etc/apache2/ports.conf
--- START ---

#NameVirtualHost *:80
NameVirtualHost *

--- END ---

chown -R www-data:www-data /var/www
chmod -R g+rw /var/www
vi /etc/apache2/apache2.conf
--- START ---

DefaultType application/x-httpd-php

--- END ---

### Domain Change ###

How I setup a new domain for my White Lightning server...
---
cd /var/
vi mysqli_connect.php

--- START ---
DEFINE ('DB_PASSWORD', 'mysecretpassword');
--- END ---

vi /root/msgrpc.rb
--- START ---
load msgrpc ServerHost=qu.gs Pass=abc123
--- END ---

screen -L -S msgrpc
msfconsole -r msgrpc.rb
--- detach: control + a -> d

touch /var/www/e/debug.log
chmod 777 //var/www/e/debug.log
vi /var/www/e/pam-i.php

--- START ---
define ('WL_DOMAIN', 'qu.gs'); /* <?php echo EXPLOIT_DOMAIN ?> */
--- END ---

cd /var/www/m/includes
vi config.inc.php
--- START ---

define ('BASE_URL', 'http://qu.gs/m/');

--- END ---
###







##Drones##
Lair takes a different approach to uploading, parsing, and ingestion of automated tool output (xml). We push this work off onto client side scripts called drones. These drones connect directly to the database. To use them all you have to do is export an environment variable "MONGO_URL". This variable is probably going to be the same you used for installation


        export MONGO_URL='mongodb://username:password@ip:27017/lair?ssl=true'

With the environment variable set you will need a project id to import data. You can grab this from the upper right corner of the lair dashboard next to the project name. You can now run any drones.


        drone-nmap <pid> /path/to/nmap.xml

You can install the drones to PATH with pip


        pip install lairdrone-<version>.tar.gz

##Contributing##
1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request 

##Setting up a development environment (OSX)##
1. Install mongodb 2.6.0 or later preferably with ssl support (`brew install mongodb --with-openssl`)
2. If using SSL then perform the following to setup certs:
  * `openssl req –new –x509 –days 365 –nodes –out mongodb-cert.crt –key out mongodb-cert.key`
  * `cat mongodb-cert.crt mongodb-cert.key > mongodb.pem`
  * Start Mongo with SSL support via mongod.conf or command line (`mongod —sslMode requireSSL —sslPEMKeyFile mongodb.pem`)
3. Add a Lair database user:
  * `mongo lair --ssl`
  * `db.createUser({user: "lair", "pwd": "yourpassword", roles:["readWrite"]});`
  * Confirm user authentication: `db.auth("lair", "yourpassword");`
4. Set the appropriate Lair environment variable...
  * With SSL:  `export MONGO_URL=mongodb://lair:yourpassword@localhost:27017/lair?ssl=true`
  * No SSL: `export MONGO_URL=mongodb://lair:password@localhost:27017/lair`
5. [Download](http://nodejs.org/download/) and install node.js
6. Install Meteor: `curl https://install.meteor.com | /bin/sh`
7. Install Meteorite package manager: `sudo npm install -g meteorite`
8. Fork the Lair project on GitHub and clone the repo locally
9. Install dependencies: `cd /path/to/lair/app && mrt` (you can kill the mrt process after dependencies are downloaded)
10. Start Lair:  `cd /path/to/lair/app && meteor`
11. Browse to http://localhost:3000
12. Code your changes and submit pull requests!

There are occasional issues and confilicts with Meteor and the Fibers module. If you run into a situation where you cannot start Meteor due to Fibers conflicts, refer to the following for potential fixes:
* [Error: Cannot find module 'fibers'](http://stackoverflow.com/questions/15851923/cant-install-update-or-run-meteor-after-0-6-release)
* [Error: fibers.node is missing](http://stackoverflow.com/questions/13327088/meteor-bundle-fails-because-fibers-node-is-missing)

##Contact##
If you need assistance with installation, usage, or are interested in contributing, please contact Dan Kottmann at any of the below.

Dan Kottmann
- [@djkottmann](https://twitter.com/djkottmann)
- djkottmann@gmail.com
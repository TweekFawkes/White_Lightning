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
    [ ] Support for SSL

##Recent Developments##
    [*] Overhauled front end, cleaned up a lot of code.
    [*] New exploits added
    [*] Administration pages
    [*] Ability to remove tasks
    [*] Added robots.txt to web root to prevent crawlers from scraping
    [*] Added License 
    
##Installation##
Setup has been verified working on January 31, 2015 on KaliLinux 1.0.9.

First, copy all directories (etc, root, var) to the root of your KaliLinux, overwritting the originals.
Make sure you have installed and have running mysql

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
    
    INSERT INTO users (name, pass) VALUES ('admin', SHA1('P@ssw0rd!'));
    UPDATE users SET user_level=1 WHERE name='gator';
    
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
    
##Apache Modifications##
    
    ln -s /etc/apache2/mods-available/proxy.load /etc/apache2/mods-enabled
    ln -s /etc/apache2/mods-available/proxy_http.load /etc/apache2/mods-enabled
    ln -s /etc/apache2/sites-available/qu.gs /etc/apache2/sites-enabled/001-qu.gs
    ln -s /etc/apache2/sites-available/blog.qu.gs /etc/apache2/sites-enabled/003-blog.qu.gs
    
vi /etc/apache2/ports.conf
    #NameVirtualHost *:80
    NameVirtualHost *

chown some files
    chown -R www-data:www-data /var/www
    chmod -R g+rw /var/www

vi /etc/apache2/apache2.conf
    DefaultType application/x-httpd-php

### Domain Change ###

How I setup a new domain for my White Lightning server...

vi /var/mysqli_connect.php
    DEFINE ('DB_PASSWORD', 'mysecretpassword');

vi /root/msgrpc.rb
    load msgrpc ServerHost=qu.gs Pass=abc123

run msf
    screen -L -S msgrpc
    msfconsole -r msgrpc.rb
       [+] detach: control + a -> d
    touch /var/www/e/debug.log
    chmod 777 //var/www/e/debug.log
    
vi /var/www/e/pam-i.php
    define ('WL_DOMAIN', 'qu.gs'); /* <?php echo EXPLOIT_DOMAIN ?> */

vi /var/www/m/includes/config.inc.php
    define ('BASE_URL', 'http://qu.gs/m/');

##NOTES##
We are still in the process of pulling out all static information and making it
fully dynamic.  But until we are done here are all the hardcoded locations that
you will need to manually modify to get things rolling:

    /var/www/e/config_e.inc.php     
        line 3: qu.gs
        line 6: 10.191.53.90
        line 8: blog.qu.gs
    /var/www/m/tasking.php
        line : blog.qu.gs
    /root/msgrpc.rc
        line 1: 10.191.53.90
    /etc/apache2/sites-available/qu.gs
        line 2: qu.gs
    /etc/apache2/sites-available/blog.qu.gs
        line 2: blog.qu.gs
        line 15: 10.191.53.90
        line 18: 10.191.53.90
    /etc/apache2/sites-available/
        file: qu.gs
        file: blog.qu.gs
    /var/mysql_connect.php
        line 9: mysecretpassword
        
###

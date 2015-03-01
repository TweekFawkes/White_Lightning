use mysql;
update user set password=PASSWORD("P@ssw0rd!mysqlroot") where User='root';
flush privileges;

CREATE DATABASE WL;
CREATE USER 'hobbyhorse'@'localhost' IDENTIFIED BY 'P@ssw0rd!mysqlwebapp';
GRANT ALL ON WL.* TO 'hobbyhorse'@'localhost';

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

INSERT INTO users (name, pass) VALUES ('gator', SHA1('P@ssw0rd!webappadmin'));
UPDATE users SET user_level=1 WHERE name='gator';

INSERT INTO users (name, pass) VALUES ('bear', SHA1('P@ssw0rd!webappuser'));

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

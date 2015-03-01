use mysql;
update user set password=PASSWORD("passwww") where User='root';
flush privileges;

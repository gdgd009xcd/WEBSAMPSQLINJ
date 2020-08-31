# WEBSAMPSQLINJ
A sample php web application with SQL injection everywhere :).  
Simple sample web application for learning SQL injection.
This sample application is a membership site for registering image files and their associated information.
This sample application consists of following items:  
* PHP
* Postgresql
* Apache 

### Prerequisite

* docker
* docker-compose

### install & setup 

1. If your os does not have docker, Please read: https://docs.docker.com/get-docker/
<PRE> e.g.  # yum install docker</PRE>  


2. If your os does not have docker-compose command, Please read: https://docs.docker.com/compose/install/
<PRE>  
 e.g.  $ su -
       # curl -L https://github.com/docker/compose/releases/download/1.21.2/docker-compose-$(uname -s)-$(uname -m) -o /usr/local/bin/docker-compose
       # chmod +x /usr/local/bin/docker-compose
</PRE>
3. download .tgz file from RELEASE page, and extract .tgz file, and create apache_php_pgsql/docker/db/data folder.
<PRE>
$ tar xzvf xxxx.tgz
$ cd WEBSAMPSQLINJ/apache_php_pgsql/docker/db
$ mkdir data
 
</PRE>
or clone git and create dir.
<PRE>
$ git clone https://github.com/gdgd009xcd/WEBSAMPSQLINJ.git
$ cd WEBSAMPSQLINJ/apache_php_pgsql/docker/db
$ mkdir data
 
</PRE>

### start application
1. Start docker using your OS method
<PRE>
e.g. (on CentOS 7)
$ su -
# systemctl start docker
</PRE>

2. In apache_php_pgsql dir, execute following docker-compose command.
<PRE>
# cd WEBSAMPSQLINJ/apache_php_pgsql
# docker-compose up -d
</PRE>

### stop application

1. In apache_php_pgsql dir, execute following docker-compose command.
<PRE>
# cd WEBSAMPSQLINJ/apache_php_pgsql
# docker-compose down
</PRE>

### URL of application 
1. Access http://localhost:8110/ in your browser.





 

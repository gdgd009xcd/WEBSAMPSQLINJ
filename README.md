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

### Sitemap

<PRE>
 *SQL - A PHP script that executes SQL statements. All SQL statements are vulnerable.
 *path - A php script that reads and displays the specified file
 
URL: http://localhost:8110/

index.php(login page)
  |
  |-[Regist New User]->newuser.php-[Confirm]->newuser.php-[Complete]->newuser.php(*SQL)->[Login]->index.php
  |
  |-[Login]-->mypage.php(*SQL)
                |
                |-[Modify user]-->moduser.php-[Confirm]->moduser.php-[Complete]->moduser.php(*SQL)-[Return to MYPAGE]->mypage.php
                |
                |-[Regist entry]-->inquiry.php-[Confirm]->confirm.php-[Complete]->complete.php(*SQL)-[Return to MYPAGE]->mypage.php
                |                                                                       |
                |                                                                       |-[xxx.img]->showfile.php(*path)
                |
                |-[Show your registered Entry list]->inquirylist.php(*SQL)-[Search]->inquirylist.php(*SQL)-[Return to MYPAGE]->mypage.php
                |                                            |                          |
                |                                            |---------[xxx.img]---------->showfile.php(*path)
                |
                |
                |-[Revoke user]-->removeuser.php-[Revoke]->removeuser.php(*SQL)-[Login]->index.php
</PRE>


### install & setup 

1. If your os does not have docker, Please read: https://docs.docker.com/get-docker/
<PRE> e.g.  # yum install docker</PRE>  


2. If your os does not have docker-compose command, Please read: https://docs.docker.com/compose/install/
<PRE>  
 e.g.  $ su -
       # curl -L https://github.com/docker/compose/releases/download/1.21.2/docker-compose-$(uname -s)-$(uname -m) -o /usr/local/bin/docker-compose
       # chmod +x /usr/local/bin/docker-compose
</PRE>
3. download Source code WEBSAMPSQLINJ-N.N.N.tar.gz file from RELEASE page, and extract .tar.gz file, and create apache_php_pgsql/docker/db/data folder for database persistence
<PRE>
e.g. version number N.N.N is 0.5.0
$ tar xzvf WEBSAMPSQLINJ-0.5.0.tgz
$ cd WEBSAMPSQLINJ-0.5.0/apache_php_pgsql/docker/db
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

### Usage of application
1. Access http://localhost:8110/help.html 
 





 

ng-cchits : AngularJS port of CCHits.net, the site where you make the charts.

Tools
=====

You will need `bower` to manager javascript dependencies, and `composer` to manager PHP dependencies.

Server
======

Here's an Apache 2 example configuration:

```apache
Listen 9090
<VirtualHost *:9090>
        ServerAdmin webmaster@localhost
        DocumentRoot /home/yannick/dev/ng-cchits
        <Directory /home/yannick/dev/ng-cchits>
                Options +Indexes +FollowSymlinks -Multiviews
                AllowOverride All
                DirectoryIndex index.html
                Require all granted
        </Directory>
        ErrorLog ${APACHE_LOG_DIR}/dev_error.log
        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn
        CustomLog ${APACHE_LOG_DIR}/dev_access.log combined
</VirtualHost>
```

Contribute
==========

Clone this repository, then install javascript and PHP dependencies :
 
```bash
$ git clone https://github.com/ymauray/ng-cchits
$ cd ng-cchits
$ composer install
$ bower install
```

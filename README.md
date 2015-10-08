ng-cchits
=========

AngularJS port of CCHits.net, the site where you make the charts.

Tools
-----

You will need `bower` to manager javascript dependencies, and `composer` to manager PHP dependencies.

Database
--------

There is no Flyway equivalent yet (I'll have a look at Phinx), so here's the basic schema to create.
This will create one account with a username 'admin' and password 'Ch@ngEME'

```mysql
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET latin1 NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `email` varchar(128) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
```
```mysql
INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', '$2a$12$0f24e2b8bf113c9f6d11eunaTstXTWqI9jb3m9HZc/1J6Jc04OdbC', 'info@euterpia-radio.fr');
```

Server
------

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

Watch it in action !
--------------------

Point your browser at `http://<server>/<path>/admin`. It should be redirected to
the login page. Login with username `admin` and password `Ch@ng3M3`, and you'll see
a very crude homepage.

Point your browser to `http://<server>/<path>/admin/#/logout` to clear the credentials.

Contribute
----------

Clone this repository, then install javascript and PHP dependencies.
After that, copy `config.example.php` to `config.php` and change it according
to your environment.
 
```bash
$ git clone https://github.com/ymauray/ng-cchits.git
$ cd ng-cchits
$ composer install
$ bower install
$ cp config.example.php config.php
$ nano config.php
```

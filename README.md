# Ads Site
###This project created with Symfony 4.0.9

###A simple ad site that has a basic functionality for creating, editing and deleting ads.


#Guide

####Clone or download from github.com
_git clone git@github.com:IhorYa/ads-site.git_

 or
 
_git clone https://github.com/IhorYa/ads-site.git_

####Go to the project folder and run 
_composer install_

####Create your database and don't forget update database schema
_php bin/console doctrine:migrations:migrate_

####That's all. The site works!
####But you can still download data to the database using fixtures
_php bin/console doctrine:fixtures:load_

By default the load command purges the database, removing all data from every table. To append your fixtures' data add the --append option.

####If you do not have a server, you can run it
_php bin/console server:run_

You must do it in project directory!

####Open your browser and navigate to http://localhost:8000/
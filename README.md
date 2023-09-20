# mvc framework
mvc framework with command console
This was written mainly from theCodeholic's version, and has deviated away from it partly due to Yoputube videos being out of order or me feeling like i could do it sooner, IOts a great series and will teach you much.


I will accept and review pull requests if you want to fork and submit ways to better teh project - but note that there are many things that i am tinkering on to improve myself.

To install so to say -- clone the repo.
run composer install 
on dev install dev to gain access to the custom console
there arent many dependencies

```
$ php js [command]
```
will run the command, we have the following commands avail out of the box.
- migrate:create [migrationName]
- migrate:run [mysqli|sqlite|pdo]
- create:command [className] [commandName]
- create:modal [modalName] [DBModel|Model]
- create:middleware [middlewareName]
- create:controller [controllerName]
- create:view [viewName]
- create:view-layout [layourName]

Creating a new command is easy to do so you can have it do what ever you need to to do for you automated like

make sure that you set up either an htaccess file I will add a working one later, or modify if able th virtual hosts file for it to use the mod rewrite to direct traffic to the correct areas::
this is and example vhost entry... 
```
<VirtualHost *:80>
	ServerName mymvc.test
	DocumentRoot "/var/www/http/mvc_framework/public"
	<Directory  "/var/www/http/mvc_framework/public/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
    Require local
    <IfModule mod_rewrite.c>
      <IfModule mod_negotiation.c>
          Options -MultiViews -Indexes
      </IfModule>
      RewriteEngine On
      # Redirect Trailing Slashes If Not A Folder...
      RewriteCond %{REQUEST_FILENAME} !-d
      RewriteCond %{REQUEST_URI} (.+)/$
      RewriteRule ^ public/%1 [QSA,L]
      # Send Requests To Front Controller...
      RewriteCond %{REQUEST_FILENAME} !-d
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteRule (.*) index.php [QSA,L]
    </IfModule>
	</Directory>
</VirtualHost>
```

for an .htaccess file it woudl look something like:
```
RewriteEngine On
# Redirect Trailing Slashes If Not A Folder...
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} (.+)/$
RewriteRule ^ public/%1 [QSA,L]
# Send Requests To Front Controller...
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule (.*) index.php [QSA,L]
```
this will force the page to hit the page that holds your routes and initalizer for the project. if you go to http://test.dev/mvc/about.php it will go to the about.php if you go to http://test.dev/mvc/about it will try to resolve the about get route or 404. it will also try to properly resolve to any directories only if they are directories ortherwise the router will be tasked with attempting to find the route
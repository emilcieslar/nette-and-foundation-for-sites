Nette + Foundation for Sites [+ Angular] Sandbox
====================================

This is a simple pre-packaged and pre-configured application using the [Nette](https://nette.org) and [Foundation for Sites](http://foundation.zurb.com/sites/docs/) + Angular is optionally added (can be of course removed without any consequences)

Installation
------------

This project already contains basic setup so what we need to do is

1. Install dependencies

  1. `composer install` installs all the PHP related stuff
  2. `npm install` installs all gulp and babel stuff (for building scss and js [you can use ES6 with babel])
  3. `bower install` installs foundation and angular

2. Don't forget to make directories `temp/` and `log/` writable using this command `chmod -R a+rw temp log`

3. Add `config.local.neon` file to `app/config/` and fill it with your local settings (this file is necessary even if it's empty), especially setup your local database credentials.

4. Fill in your base server email and server url in `app/config/config.neon`. Users will receive emails with `from:` specified with this email.

5. Install database using `app/install/install.sql` script in, for instance, phpMyAdmin or in terminal. This will also insert a first test user with username (=email) you specify in the install.sql script. Make sure it's your email because you're gonna need to generate a password for this user. After you build your database, you can go to `yoursite/admin/` where you'll be redirected to login page where you can head over to generate a new password. Alternatively, if you have access to the server's terminal (on localhost usually), you can run (while in your project folder) `php bin/create-user.php your_email your_password` and this will create a new user for you

6. Run `gulp` to set up the css and javascript

7. Run `gulp watch` if you want to watch scss and scripts folders changes

Project structure
-----------------
This project is separated into modules from start, the modules are:

1. **Front**
This is module accessible by everyone, it's basically what a consumer of your site sees

2. **Admin**
This module is devoted to admins, you can add presenters that administer the site. The module is only accessible by user with role = 'admin'

3. **Password**
This module takes care of forgotten passwords

License
-------
- Nette: New BSD License or GPL 2.0 or 3.0

Notes
-----
- If you don't setup your database in your config file, it will throw this sort of error:
```
Service of type Nette\Database\Context needed by App\Model\Repository\Repository::__construct() not found. Did you register it in configuration file?
```

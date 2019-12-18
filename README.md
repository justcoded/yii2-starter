<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 JustCoded Project Template</h1>
    <br>
</p>

Yii 2 JustCoded Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
rapidly creating projects with admin interface and RBAC.

The template contains the basic features including contact page, user login/logout, admin panel with 
users management, route-based RBAC access control, fixture examples.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.


DIRECTORY STRUCTURE
-------------------

      app/                contains your application classes and files
        |-- assets           css/js assets and AssetBundle classes
        |-- base             base classes (overwrite yii\base or few more) 
        |-- components       application "components" classes 
        |-- console          controllers for console commands
        |-- controllers      controllers for web application
        |-- filters          application filters (similar to yii\filters) 
        |-- forms            various form models 
        |-- mail             view files for e-mails 
        |-- models           ActiveRecord model classes 
        |-- modules          connected modules, admin panel module by default
        |-- rbac             RBAC Manager / components 
        |-- traits           global traits, grouped by type 
        |-- views            view files for the Web application
        |-- widgets          application widgets to use inside views 
      config/             contains application configurations
      database/           contains migration and fixtures
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      public/             contains public assets and web entry script index.php / server docroot

The difference from [Yii2 basic template](https://github.com/yiisoft/yii2-app-basic):

* Improved IDE support for custom components (`Yii::$app->getComponent(...)`)
* Application code is moved to it's own folder `/app`
* Config files are moved outside application code folder and use .env extension (ability to get values from server ENV variables or .env file)
* `commands` folder is renamed to `console` (because inside we actually have Controllers, not Commands).
* Form models have their own folder to separate them from ActiveRecord models
* `assets` folder is used to store public assets as well (to be able to publish assets in the same way for app / modules / widgets)
* Admin module with CRUD example (Users management)
* Advanced RBAC based on 4 default roles and route-based access control. See [justcoded/yii2-rbac](https://github.com/justcoded/yii2-rbac)
* Application params replaced with settings extensions (IDE autocompletion available). See [justcoded/yii2-settings](https://github.com/justcoded/yii2-settings)


REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.0.


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

	php composer.phar create-project --prefer-dist justcoded/yii2-starter my-project

or 

	composer create-project --prefer-dist justcoded/yii2-starter my-project

CONFIGURATION
-------------

### ENV support

Config files are the same for all environments. You don't need to create some "local" config files.
Instead you can accept different parameters from server environment with `env()` helper function. 

Server environment variables can be set through web server vhost configuration, .htaccess file, 
or .env file in project root (the simplest option).

To start using the project template copy .env.example as .env in the project root and setup it.

### Web
Copy `/public/.htaccess.example` as `/public/.htaccess` to enable pretty URLs support and cache/expire 
tokens required by Google PageSpeed Insights test.

Furthermore you should check such options inside .env:

```php
APP_ENV=dev
APP_DEBUG=true
APP_KEY=wUZvVVKJyHFGDB9qK_Lop4QE1vwb4bYU
```

*`APP_KEY` is used as CSRF token (cookie verification key). In order to set or change it, run:
 
```bash
php yii security/app-key
```

### Database

You should update your .env file config:

```php
DB_HOST=127.0.0.1
DB_NAME=yii2_starter
DB_USER=root
DB_PASS=12345
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- By default starter configured to work with MySQL database. If you want to use another database you need to configure dsn string inside `config/db.php` (or define env variable like `DB_DSN="mysql:host={host};dbname={dbname}"`).

LAUNCH
-------

You will need to create required tables through migrations and init RBAC extension.
Launch the commands below from terminal:

```bash
php yii migrate
php yii fixture/load User
php yii rbac/init
php yii rbac/assign-master 1
php yii rbac/scan
php yii rbac/scan --path=@vendor/justcoded/yii2-rbac/ --routesBase=admin/rbac/
```

Now you should be able to access the application through the following URL, assuming `my-project` is the directory
directly under the Web root.

	http://localhost/my-project/public/

Or you can run `yii serve` to launch Yii built-in web server, similar to usual Yii basic application.

Admin panel can be accessible only after login. If you used fixtures to fill the database with dummy content,
then admin panel access will be:

	http://localhost/my-project/public/admin/
	User:       admin@domain.com
	Password:   password_0

TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).
By default there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](http://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2_basic_tests` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run -- --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit -- --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit -- --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.

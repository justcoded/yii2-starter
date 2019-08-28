CHANGELOG for Yii2 STARTER PROJECT TEMPLATE
=====================

*should be replaced with real project changelog later*

v2.0.1
---------------------
* Fixed admin panel pages with Select2 Kartik widgets (enabled Bootstrap4 mode through Yii::$app params)

v2.0
---------------------
* Replaced Bootstrap 3 with Bootstrap 4
* Replaced AdmitLTE 2.x admin theme (based on Bootstrap 3) with AdminLTE 3.0-beta (based on Bootstrap 4)
* Refactored using View blocks: new Block widget

v1.0
---------------------
* Added APP_KEY hash generator console command.
* Base module has been removed 

v0.9.6
---------------------
* Fix register page, controller missed use statement for RegisterForm.

v0.9.5
---------------------
* Fix yii rbac/scan for Windows OS (SettingsController generated an error for some reason)

v0.9.4
---------------------
* Updated configs to PHP 7 syntax (with ::class call, instead of hard-coded text value)

v0.9.3
---------------------
* Fix './yii serve' command.
* Updated configs to PHP 7 syntax (with ::class call, instead of hard-coded text value)

v0.9.2
---------------------
* Fix user migration after code refactoring in v0.9

v0.9.1
---------------------
* Fix Codeception tests errors when run through PHPStorm.

v0.9
---------------------
* Upgrade app folder structure: moved controllers from web to root, moved commands to console, 
* Moved migration console/views custom configuration to a separate module.
* Admin panel: Added user "Roles" fields to edit form.
* Issue #13: Registration is absent
* Issue #14: Reset password

v0.8.6
---------------------
* Fixed unit/functional tests

v0.8.5
---------------------
* Issue #19: Updated README about how to access the site
* Issue #20: Admin panel chrome console error: not working adminlte js
* Issue #21: Replace custom Application classes with simple IDE helper file with definitions
* Issue #22: "Clickjacking" attack fix

v0.8.4
---------------------
* Added prefer-stable directive to composer.json
* Added optimize autoloader directive to composer.json

v0.8.3
---------------------
* Renamed root "web" folder to "public"

v0.8.2
---------------------
* User model validation rules

v0.8.1
---------------------
* Update versions dependency for Yii to 2.0.12

v0.8
---------------------
* Moved RBAC as separate extension.
* Added RBAC GUI to admin module

v0.7
---------------------
* Moved settings to external package
* Fixed settings migration path

v0.6
---------------------
* Added app components settings class to support IDE for modelsMap
* Overwrite all params usage to settings usage
* Updated AppSettingsForm sender* properties to system* properties. 

v0.5
---------------------
* Added settings extension with settings component
* Added settings controller in admin module

v0.4
---------------------
* Renamed back theme folder into assets, added example of using images from asset manager

v0.3
---------------------
* RBAC moved as extension (to be moved to separate package later)
* Removed settings component (will be added later when UI/Architecture wireframed/approved)

v0.2
---------------------
* Removed ApplicationParams class
* Created basic Settings component - TBU with AR models in future
* Moved Migration class

v0.1
---------------------
* Basic template file structure improved
* Dotenv support
* Codeception tests are running okay
* Migration custom templates / custom methods
* Fixtures moved to /database/fixtures
* Added User with AR from advanced template
* Admin module with dummy dashboard
* Base / Admin themes structure
* Admin 404 page
* Admin users management
* DB RBAC component
* DB RBAC routes scanner
* Updated intro README file

TODO:
----------------------
* RBAC Admin GUI
* forms: sign up / forgot

<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii2 Settings Extension</h1>
    <br>
</p>


Replacement for Yii app params. Easy to use component to store application settings.
Supports only DB storage for now.
Have ready to use base Settings form model and controller Action.

### Installation

The preferred way to install this extension is through composer.

Either run

```bash
php composer.phar require --prefer-dist yii2mod/yii2-settings "*"
```

or add

```
"yii2mod/yii2-settings": "*"
```

to the require section of your composer.json.

### Configuration

#### Database migration

Before usage this extension, we'll also need to prepare the database.

You can add migrations path to your console config and then run `migrate` command:

```php
'migrate' => [
    'migrationPath' => [
        '@vendor/justcoded/yii2-settings/migrations'
    ],
],
```

or you can run the command below:

```
php yii migrate --migrationPath=@vendor/justcoded/yii2-settings/migrations
```

### Component Setup

To use the Setting Component, you need to configure the components array in your application configuration:

```php
'components' => [
    'settings' => [
        'class'     => 'justcoded\yii2\settings\components\DbSettings',
    ],
],
```

and add component name to bootstrap array

```php
    'bootstrap'  => ['log', 'settings'],
```

### Usage

```php
// set value
Yii::$app->settings->set('section_name', 'key', 'value');

// get value
$value = Yii::$app->settings->get('section_name', 'key');
```

There is a possibility to use models as some setting group object. To do this you have to
add modelsMap array to component's configuration:

```php
    'settings' => [
        'class'     => 'justcoded\yii2\settings\components\DbSettings',
        'modelsMap' => [
            'section1' => 'app\models\MySettingsForm1',
            'section2' => 'app\models\MySettingsForm2',
        ],
    ],
```

Add action to controller to get settings form with keys according to the model's properties

```php
    public function actions()
    {
        return [
            'actionName' => [
                'class' => 'justcoded\yii2\settings\actions\SettingsAction',
                'modelClass' => 'app\models\MySettingsForm1',
            ],
        ];
    }
```
and create view with some active form. (You can copy a template from extension "views" folder) 

Now you can get settings in better way:

```php
$value = Yii::$app->settings->section1->myPropertyName;
```

This is very useful, if you overwrite Yii/Application classes and specify correct PHPDoc comments. 
In this way IDE will highlight all sections and properties.

### Example

You can check the example on our [Yii2 starter kit](https://github.com/justcoded/yii2-starter).
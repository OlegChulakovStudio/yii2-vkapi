# Yii2 component for VK API
[Russian](README.ru.md)

## Install by composer
composer require oleg-chulakov-studio/yii2-vkapi
## Or add this code into require section of your composer.json and then call composer update in console
"oleg-chulakov-studio/yii2-vkapi": "*"
## Usage
In configuration file do
```php
<?php
  'components'  =>  [
        'class' => OlegChulakovStudio\YiiVkComponent::className(),
        'clientId' => '',
        'secretCode' => '',
        'accessToken' => '',
        'apiVersion' => '5.67'
  ]
 ?>
 ```
 Use as simple component
 ```php
<?php
$responseData = Yii::$app->YiiVk->request('newsfeed.search', [
    'count' => 100,
    'q' => '#tag',
    'extended' => true,
]);
?>
 ```

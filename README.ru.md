# Yii2 компонент для VK API
[English](README.md)

## Установка через composer
composer require oleg-chulakov-studio/yii2-vkapi
## Или добавьте эту строку в секцию require файла composer.json и выполните команду composer update в консоли
"oleg-chulakov-studio/yii2-vkapi": "*"
## Использование
В конфигурационном файле пропишите:
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
Простой пример использования
 ```php
<?php
$responseData = Yii::$app->YiiVk->request('newsfeed.search', [
    'count' => 100,
    'q' => '#tag',
    'extended' => true,
]);
?>

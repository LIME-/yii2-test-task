# Тестовая задача

>Создать Yii2-приложение, обрабатывающее один запрос, принимающий на вход целое число и возвращающее список всех простых делителей. Т.е. этот запрос должен сказать мне, что 4=2х2, 60=2х2х3х5, а 1024=2х2х2х2х2х2х2х2х2х2

* https://github.com/LIME-/yii2-test-task/blob/master/controllers/FactorizationController.php
* https://github.com/LIME-/yii2-test-task/blob/master/services/FactorizationService.php
* https://github.com/LIME-/yii2-test-task/blob/master/tests/unit/FactorizationServiceTest.php
* https://github.com/LIME-/yii2-test-task/blob/master/views/factorization/index.php

url  http://domain-name/factorization

При вводе чисел менее 2 или более максимально возможного 2147483647 / 9223372036854775807 (зависит от разрядности системы) выбрасываются сообщения об ошибке.
Также если присутствуют не цифры.

запуск тестов:
~~~
$ php ./vendor/bin/codecept run unit
~~~

_Разворачивал проект с ключем "minimum-stability": "dev", потому как не успели еще зарелизить фикс DI в параметры экшена.
Иначе можно зарегистрировать сервис как компонент в конфиге и вызывать через контейнер сервис локатор \Yii::$app->factorization._

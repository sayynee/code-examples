<?php

/*
 * Автор кода: SayyNee
 * https://sayynee.ru/
 * 
 * В проекте используется сторонняя библиотека
 * KrugozorDatabase (https://github.com/Vasiliy-Makogon/Database)
 * 
 */

// подключаем библиотеку MySQL
require_once($_SERVER['DOCUMENT_ROOT'] . "/system/db/autoload.php");
use Krugozor\Database\Mysql;

// вводим данные от БД
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "test";

$db = Mysql::create($db_host, $db_user, $db_pass) // подключаемся к серверу БД
    ->setDatabaseName($db_name) // выбираем БД
    ->setCharset("utf8"); // выбираем кодировку

session_start(); // стартуем сессию

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['session_hash'])) { // проверяем, передали ли нам данные

    if ($_POST['session_hash'] == $_SESSION['session_hash']) { // сверяем хэш

        $user = $db->query("SELECT * FROM `users` WHERE `username` = '?s'", $_POST['username'])->fetchAssoc(); // получаем данные о пользователе

        if (isset($user)) { // проверяем, были ли получены данные (есть ли такой пользователь)

            if (password_verify($_POST['password'], $user['password'])) { // сверяем хэш паролей

                echo "ok"; // вход произведен

            } else {
                echo "incorrect_password"; // выводим ошибку, если пароль неверный
            }

        } else {
            echo "unknown_user"; // выводим ошибку, если такого пользователя нет
        }

    } else {
        echo "hacking_attempt"; // выводм ошибку, если хэш не совпадает
    }

} else {
    echo "argument_error"; // выводим ошибку, если не все данные отправлены
}

?>
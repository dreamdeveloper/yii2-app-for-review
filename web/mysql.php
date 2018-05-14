<?php
$server = 'sql27.webhuset.no';
$user = 'studentpakkenn1';
$password = '7roVIBA';

$dblink = mysql_connect($server, $user, $password);

if($dblink)
echo 'Соединение установлено.';
else
die('Ошибка подключения к серверу баз данных.');

$database = 'studentpakkenn1';

$selected = mysql_select_db($database, $dblink);
if($selected)
echo ' Подключение к базе данных прошло успешно.';
else
die(' База данных не найдена или отсутствует доступ.');
?>
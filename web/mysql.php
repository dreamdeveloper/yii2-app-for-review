<?php
$server = 'sql27.webhuset.no';
$user = 'studentpakkenn1';
$password = '7roVIBA';

$dblink = mysql_connect($server, $user, $password);

if($dblink)
echo '���������� �����������.';
else
die('������ ����������� � ������� ��� ������.');

$database = 'studentpakkenn1';

$selected = mysql_select_db($database, $dblink);
if($selected)
echo ' ����������� � ���� ������ ������ �������.';
else
die(' ���� ������ �� ������� ��� ����������� ������.');
?>
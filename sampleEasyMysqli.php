<?php
require('EasyMysqli.php');
/*
 * DB TEST
 *
DROP TABLE IF EXISTS `tb_users`;
CREATE TABLE IF NOT EXISTS `tb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
 *
*/

$db = new Database();

$table = 'tb_users';
//If you want to debug SQL user the debug true
$debug = false;

$arrayInsert['user']  = 'userName';
$arrayInsert['email'] = 'userEmail';
$arrayInsert['pass']  = 'userPass'; // ENCRYPT IT

//Using insert command
$db->insert($table, $arrayInsert, $debug);
//You can omit $debug like this
//$db->insert($table, $arrayInsert);

//Use simple select command
$result = $db->select($table);

//Using select with WHERE
$whereSelect = 'WHERE id = 1';
$result = $db->select($table, $whereSelect);

//Get result by select
$row = $db->feelArray($result);

//Using update command
$arrayWhere['user']  = 'updateUserName';
$arrayWhere['email'] = 'updateUserEmail';
$arrayWhere['pass']  = 'updateUserPass'; // ENCRYPT IT

$arrayWhere['id'] = 1;
$db->update($table, $arrayUpdate, $arrayWhere);

//Using delete command
$arrayDelete['id'] = 1;
$db->insert($table, $arrayDelete);

<?php
require '../DB.php';
$db = DB::getInstance();
$dbConn = $db->getDBConn();
$sql = <<<db
CREATE TABLE `user` (
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `studentID` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` date NOT NULL,
  `admitted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY ( `studentID` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
db;
$dbConn->query($sql);
echo $dbConn->error;
echo '创建数据表命令已经执行，请查看是否有错误输出！';

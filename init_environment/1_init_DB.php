<?php
require "../common.php";
setContentType();
$db = &DB::getInstance();
$dbConn = &$db->getDBConn();
$sql = <<<sql
CREATE TABLE `user` (
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `studentID` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` DATETIME NOT NULL,
  `admitted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY ( `studentID` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
sql;
$dbConn->query($sql);
echo $dbConn->error . '<br/>';

$sql = <<<sql
CREATE TABLE `article` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `time` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;
sql;
$dbConn->query($sql);
echo $dbConn->error . '<br/>';

$sql = <<<sql
CREATE TABLE `message` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `articleId` INT NOT NULL,
    `message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `studentID` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `time` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;
sql;
$dbConn->query($sql);
echo $dbConn->error . '<br/>';

echo '创建数据表命令已经执行，请查看是否有错误输出！';

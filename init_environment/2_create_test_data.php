<?php
header("Content-type: text/html; charset=utf-8");
require '../DB.php';
$db = &DB::getInstance();
$db->addUser('root', '01', 'test');
$db->addUser('Beck', '02', 'test');
$db->addUser('test', '03', 'test');
$db->addUser('debug', '04', 'test');
$db->addUser('null', '05', 'test');
$db->admitUser('01');
$db->admitUser('02');
$db->admitUser('03');
$db->admitUser('04');
echo '创建测试用户命令已执行完成...<br/>';

$db->addArticle('testArticle1', 'testContent1');
$db->addArticle('testArticle2', 'testContent2');
$db->addArticle('testArticle3', 'testContent3');
echo '创建测试文章命令已执行完成....<br/>';

$db->addMessage(1, 'testComment1', '01');
$db->addMessage(1, 'testComment2', '01');
echo '创建测试留言命令已执行完成....<br/>';

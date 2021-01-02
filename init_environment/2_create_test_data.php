<?php
require "../common.php";
setContentType();
$db = &DB::getInstance();
//////////////////////   管理员预留账户   /////////////////////////
$db->addUser('admin', 'admin', 'test');
$db->denyUser('admin');
////////////////////////////////////////////////////////////////

$db->addUser('root', '01', 'test');
$db->addUser('Beck', '02', 'test');
$db->addUser('test', '03', 'test');
$db->addUser('debug', '04', 'test');
$db->addUser('null', '05', 'test');
$db->admitUser('01');
$db->admitUser('02');
$db->admitUser('03');
$db->denyUser('05');
echo '创建测试用户命令已执行完成...<br/>';

$db->addArticle('testArticle1', 'testContent1');
$db->addArticle('testArticle2', 'testContent2');
$db->addArticle('testArticle3', 'testContent3');
$db->addArticle('testArticle4', 'testContent4');
$db->addArticle('testArticle5', 'testContent5');
echo '创建测试文章命令已执行完成....<br/>';

$db->addMessage(1, 'testComment1', '01');
$db->addMessage(1, 'testComment2', '02');
$db->addMessage(1, 'testComment3', '03');
$db->addMessage(1, 'testComment4', '04');
$db->addMessage(1, 'testComment5', '05');
echo '创建测试留言命令已执行完成....<br/>';

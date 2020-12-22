<?php
require '../DB.php';
$db = &DB::getInstance();
$db->addUser('root', '01', 'test');
$db->addUser('Beck', '02', 'test');
$db->addUser('test', '03', 'test');
$db->addUser('debug', '04', 'test');
$db->addUser('null', '05', 'test');
echo '创建测试用户命令已执行完成...';

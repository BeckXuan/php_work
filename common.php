<?php

function &getDBConn()
{
    static $conn;
    if (is_object($conn) == true) {
        return $conn;
    }
    require "DBConfig.php";
    /** @noinspection PhpUndefinedVariableInspection */
    $conn = new mysqli($host, $user, $password, $dbname, $port);
    $conn->query('set names "utf8"');
    return $conn;
}

function filter($str)
{
    if (PHP_VERSION >= 6 || !get_magic_quotes_gpc()) { // 判断magic_quotes_gpc是否为打开
        $str = addslashes($str); // magic_quotes_gpc没有打开的时候把数据过滤
    }
    $str = str_replace("_", "\_", $str); // 把 '_'过滤掉
    $str = str_replace("%", "\%", $str); // 把' % '过滤掉
    $str = nl2br($str); // 回车转换
    //$str = htmlspecialchars($str); // html标记转换
    return $str;
}

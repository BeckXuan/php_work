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

function &sqlQuery($sql)
{
    $conn = &getDBConn();
    $result = $conn->query($sql);
    return $result;
}

function studentIDExists($studentID, $needFilter = true)
{
    if ($needFilter) {
        $studentID = filter($studentID);
    }
    $result = &sqlQuery("select * from user where studentID='$studentID' limit 1");
    return boolval($result->num_rows);
}

function addUser($name, $studentID, $password, $needMD5 = true, $needFilter = true)
{
    if ($needFilter) {
        $name = filter($name);
        $studentID = filter($studentID);
    }
    if ($needMD5) {
        $password = md5($password);
    }
    return sqlQuery("INSERT INTO user(name, studentID, password, date) VALUES ('$name', '$studentID', '$password', CURDATE())");
}

function admitUser($studentID, $needFilter = true)
{
    if ($needFilter) {
        $studentID = filter($studentID);
    }
    return sqlQuery("UPDATE user SET admitted=1 WHERE studentID='$studentID'");
}

function denyUser($studentID, $needFilter = true)
{
    if ($needFilter) {
        $studentID = filter($studentID);
    }
    return sqlQuery("UPDATE user SET admitted=0 WHERE studentID='$studentID'");
}

function delUser($studentID, $needFilter = true)
{
    if ($needFilter) {
        $studentID = filter($studentID);
    }
    return sqlQuery("DELETE FROM user WHERE studentID='$studentID'");
}

function changeUserName($studentID, $name, $needFilter = true)
{
    if ($needFilter) {
        $studentID = filter($studentID);
    }
    return sqlQuery("UPDATE user SET name='$name' WHERE studentID='$studentID'");
}

function changeUserPassword($studentID, $password, $needMD5 = true, $needFilter = true)
{
    if ($needFilter) {
        $studentID = filter($studentID);
    }
    if ($needMD5) {
        $password = md5($password);
    }
    return sqlQuery("UPDATE user SET password='$password' WHERE studentID='$studentID'");
}

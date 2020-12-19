<?php

function &getDBConn()
{
    static $conn;
    if (is_object($conn)) {
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

function getSqlError() {
    $conn = &getDBConn();
    return $conn->error;
}

function studentIDExists($studentID, $paramFilter = true)
{
    if ($paramFilter) {
        $studentID = filter($studentID);
    }
    $result = &sqlQuery("select name from user where studentID='$studentID' limit 1");
    return boolval($result->num_rows);
}

function addUser($name, $studentID, $password, $needMD5 = true, $paramFilter = true)
{
    if ($paramFilter) {
        $name = filter($name);
        $studentID = filter($studentID);
    }
    if ($needMD5) {
        $password = md5($password);
    }
    return sqlQuery("INSERT INTO user(name, studentID, password, date) VALUES ('$name', '$studentID', '$password', CURDATE())");
}

function admitUser($studentID, $paramFilter = true)
{
    if ($paramFilter) {
        $studentID = filter($studentID);
    }
    return sqlQuery("UPDATE user SET admitted=1 WHERE studentID='$studentID'");
}

function denyUser($studentID, $paramFilter = true)
{
    if ($paramFilter) {
        $studentID = filter($studentID);
    }
    return sqlQuery("UPDATE user SET admitted=0 WHERE studentID='$studentID'");
}

function delUser($studentID, $paramFilter = true)
{
    if ($paramFilter) {
        $studentID = filter($studentID);
    }
    return sqlQuery("DELETE FROM user WHERE studentID='$studentID'");
}

function getUserStudentID($name, $paramFilter = true, $outHTMLFilter = true) {
    if ($paramFilter) {
        $name = filter($name);
    }
    $result = &sqlQuery("SELECT studentID From user WHERE name='$name' limit 1");
    if (!$result->num_rows) {
        return null;
    }
    $out = $result->fetch_assoc()['studentID'];
    if ($outHTMLFilter) {
        $out = htmlspecialchars($out);
    }
    return $out;
}

function getUserInformation($studentID, $type, $paramFilter = true, $outHTMLFilter = true) {
    if ($paramFilter) {
        $studentID = filter($studentID);
    }
    $result = &sqlQuery("SELECT `$type` From user WHERE studentID='$studentID' limit 1");
    if (!$result->num_rows) {
        return null;
    }
    $out = $result->fetch_assoc()[$type];
    if ($outHTMLFilter) {
        $out = htmlspecialchars($out);
    }
    return $out;
}

function getUserName($studentID, $paramFilter = true, $outHTMLFilter = true) {
    return getUserInformation($studentID, 'name', $paramFilter, $outHTMLFilter);
}

function getUserPassword($studentID, $paramFilter = true, $outHTMLFilter = true) {
    return getUserInformation($studentID, 'password', $paramFilter, $outHTMLFilter);
}

function setUserStudentID($studentID, $newID, $paramFilter = true)
{
    if ($paramFilter) {
        $studentID = filter($studentID);
    }
    return sqlQuery("UPDATE user SET studentID='$newID' WHERE studentID='$studentID'");
}

function setUserName($studentID, $name, $paramFilter = true)
{
    if ($paramFilter) {
        $studentID = filter($studentID);
    }
    return sqlQuery("UPDATE user SET name='$name' WHERE studentID='$studentID'");
}

function setUserPassword($studentID, $password, $needMD5 = true, $paramFilter = true)
{
    if ($paramFilter) {
        $studentID = filter($studentID);
    }
    if ($needMD5) {
        $password = md5($password);
    }
    return sqlQuery("UPDATE user SET password='$password' WHERE studentID='$studentID'");
}

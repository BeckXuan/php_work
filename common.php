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

function getSqlError() {
    $conn = &getDBConn();
    return $conn->error;
}

function studentIDExists($studentID)
{
    $conn = &getDBConn();
    $stmt = $conn->prepare('select name from user where studentID=? limit 1');
    $stmt->bind_param('s', $studentID);
    $stmt->execute();
    $existed = boolval($stmt->get_result()->num_rows);
    $stmt->close();
    return $existed;
}

function addUser($name, $studentID, $password, $needMD5 = true)
{
    if ($needMD5) {
        $password = md5($password);
    }
    $conn = &getDBConn();
    $stmt = $conn->prepare('INSERT INTO user(name, studentID, password, date) VALUES (?, ?, ?, CURDATE())');
    $stmt->bind_param('sss', $name, $studentID, $password);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function setUserAccess($studentID, $admitted) {
    $conn = &getDBConn();
    $stmt = $conn->prepare('UPDATE user SET admitted=? WHERE studentID=?');
    if ($admitted) {
        $admitted = 1;
    } else {
        $admitted = 0;
    }
    $stmt->bind_param('is', $admitted,$studentID);
    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }
    $result = boolval($stmt->affected_rows);
    $stmt->close();
    return $result;
}

function admitUser($studentID)
{
    return setUserAccess($studentID, 1);
}

function denyUser($studentID)
{
    return setUserAccess($studentID, 0);
}

function delUser($studentID)
{
    $conn = &getDBConn();
    $stmt = $conn->prepare('DELETE FROM user WHERE studentID=?');
    $stmt->bind_param('s', $studentID);
    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }
    $result = boolval($stmt->affected_rows);
    $stmt->close();
    return $result;
}

function getUserInformation($studentID, $type, $outHTMLFilter = true) {
    $conn = &getDBConn();
    $stmt = $conn->prepare("SELECT `$type` From user WHERE studentID=? limit 1");
    $stmt->bind_param('s', $studentID);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->num_rows) {
        $stmt->close();
        return null;
    }
    $out = $result->fetch_array()[0];
    $stmt->close();
    if ($outHTMLFilter) {
        $out = htmlspecialchars($out);
    }
    return $out;
}

function getUserName($studentID, $outHTMLFilter = true) {
    return getUserInformation($studentID, 'name', $outHTMLFilter);
}

function getUserPassword($studentID, $outHTMLFilter = true) {
    return getUserInformation($studentID, 'password', $outHTMLFilter);
}

function setUserInformation($studentID, $type, $value) {
    $conn = &getDBConn();
    $stmt = $conn->prepare("UPDATE user SET `$type`=? WHERE studentID=?");
    $stmt->bind_param('ss',$value, $studentID);
    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }
    $result = boolval($stmt->affected_rows);
    $stmt->close();
    return $result;
}

function setUserStudentID($studentID, $newID)
{
    return setUserInformation($studentID, 'studentID', $newID);
}

function setUserName($studentID, $newName)
{
    return setUserInformation($studentID, 'name', $newName);
}

function setUserPassword($studentID, $newPassword, $needMD5 = true)
{
    if ($needMD5) {
        $newPassword = md5($newPassword);
    }
    return setUserInformation($studentID, 'password', $newPassword);
}

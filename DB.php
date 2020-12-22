<?php

class DB
{
    private $_db;
    private static $_instance;

    private function __construct()
    {
        require "DBConfig.php";
        /* @noinspection PhpUndefinedVariableInspection */
        $this->_db = new mysqli($host, $user, $password, $dbname, $port);
        $error = $this->_db->connect_error;
        if ($error) {
            die('数据库连接失败！错误信息: ' . $error);
        }
        $this->_db->query('set names "utf8"');
    }

    private function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public static function &getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function &getDBConn()
    {
        return $this->_db;
    }

    public function getSqlError()
    {
        return $this->_db->error;
    }

    private function contentExists($field, $value)
    {
        $stmt = $this->_db->prepare("select name from user where `$field`=? limit 1");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $existed = boolval($stmt->get_result()->num_rows);
        $stmt->close();
        return $existed;
    }

    public function studentIDExists($studentID)
    {
        return $this->contentExists('studentID', $studentID);
    }

    public function nameExists($name) {
        return $this->contentExists('name', $name);
    }

    public function addUser($name, $studentID, $password, $needMD5 = true)
    {
        if ($needMD5) {
            $password = md5($password);
        }
        $stmt = $this->_db->prepare('INSERT INTO user(name, studentID, password, date) VALUES (?, ?, ?, CURDATE())');
        $stmt->bind_param('sss', $name, $studentID, $password);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    private function setUserAccess($studentID, $admitted)
    {
        $stmt = $this->_db->prepare('UPDATE user SET admitted=? WHERE studentID=?');
        if ($admitted) {
            $admitted = 1;
        } else {
            $admitted = 0;
        }
        $stmt->bind_param('is', $admitted, $studentID);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = boolval($stmt->affected_rows);
        $stmt->close();
        return $result;
    }

    public function admitUser($studentID)
    {
        return $this->setUserAccess($studentID, 1);
    }

    public function denyUser($studentID)
    {
        return $this->setUserAccess($studentID, 0);
    }

    public function delUser($studentID)
    {
        $stmt = $this->_db->prepare('DELETE FROM user WHERE studentID=?');
        $stmt->bind_param('s', $studentID);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = boolval($stmt->affected_rows);
        $stmt->close();
        return $result;
    }

    private function getUserStudentId($field, $value)
    {
        $stmt = $this->_db->prepare("SELECT studentID From user WHERE `$field`=? limit 1");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows) {
            $stmt->close();
            return null;
        }
        $out = $result->fetch_array()[0];
        $stmt->close();
        return $out;
    }

    public function getUserStudentIdByName($name)
    {
        return $this->getUserStudentId('name', $name);
    }

    private function getUserInformation($studentID, $field, $outHTMLFilter = true)
    {
        $stmt = $this->_db->prepare("SELECT `$field` From user WHERE studentID=? limit 1");
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

    public function getUserName($studentID, $outHTMLFilter = true)
    {
        return $this->getUserInformation($studentID, 'name', $outHTMLFilter);
    }

    public function getUserPassword($studentID, $outHTMLFilter = true)
    {
        return $this->getUserInformation($studentID, 'password', $outHTMLFilter);
    }

    public function isUserAccessible($studentID)
    {
        return boolval($this->getUserInformation($studentID, 'admitted', false));
    }

    private function setUserInformation($studentID, $field, $value)
    {
        $stmt = $this->_db->prepare("UPDATE user SET `$field`=? WHERE studentID=?");
        $stmt->bind_param('ss', $value, $studentID);
        if (!$stmt->execute()) {
            $stmt->close();
            return null;
        }
        $result = boolval($stmt->affected_rows);
        $stmt->close();
        return $result;
    }

    public function setUserStudentID($studentID, $newStudentID)
    {
        return $this->setUserInformation($studentID, 'studentID', $newStudentID);
    }

    public function setUserName($studentID, $newName)
    {
        return $this->setUserInformation($studentID, 'name', $newName);
    }

    public function setUserPassword($studentID, $newPassword, $needMD5 = true)
    {
        if ($needMD5) {
            $newPassword = md5($newPassword);
        }
        return $this->setUserInformation($studentID, 'password', $newPassword);
    }
}

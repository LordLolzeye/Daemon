<?php
require "Utils/Config.php";
require "Utils/GetFileContents.php";
require "Utils/SendMail.php";

if (isset($_GET['password'])) {
    $pass     = $_GET['password'];
    $clientip = $_SERVER['REMOTE_ADDR'];
    if ($pass == $token) {
        $db     = new SQLite3("queue.db");
        $result = $db->query("SELECT * FROM `queue` WHERE `status` = 'inQueue'");
        while($row = $result->fetchArray()) {
            $fileContent = readFileForMail($row['localDir'] . "/current/results");
            if ($fileContent != "ERROR") {
                sendMail($row['email'], $emailSenderAddress, $emailSubject, $fileContent);
                $db->query("UPDATE `queue` set `status` = 'COMPLETED' WHERE `user` = '" . $row['user'] . "'");
            }
        }
    }
}

?>
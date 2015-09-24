<?php
/*
GitLab-ul va trimite un request de genul
hook.php?password=ceva + un post cu restul lucrurilor.
impreuna cu un $_POST care contine toate
datele despre event-ul pentru care facem listen
*/

require "Utils/Config.php";
require "Utils/Archiver.php";
require "Utils/DownloadDir.php";

if (isset($_GET['password'])) {
    $pass     = $_GET['password'];
    $clientip = $_SERVER['REMOTE_ADDR'];
    if (($pass == $token) && ($clientip == $gitlabIP)) {
        if (isset($_POST['ceva'])) {
            if (!empty($_POST['ceva'])) {
                $acelCeva = $_POST['ceva'];
                $ok       = 0;
                foreach ($_POST['commits'] as $commit) {
                    if (strpos($commit['message'], $keyWord) !== false) {
                        $ok = 1;
                        break;
                    }
                }
                if ($ok == 1) {
                    $repository  = $_POST['repository']['git_http_url'];
                    $submitUser  = $_POST['user_name'];
                    $submitEmail = $_POST['user_email'];
                    
                    $localDirForDB = "/home/vmchecker/storer-vmchecker/repo/?????/" . $submitUser;
                    $localDir      = $localDirForDB . "/lastsubmit";
                    exec("rm -rf " . $localDir);
                    exec("mkdir -p " . $localDir);
                    exec("cd " . $localDir);
                    exec("git clone " . $repository);
                    
                    doZip($localDir, "/var/www/VMChecker/Queue/" . $submitUser . ".zip");
                    //Try to put file into QUEUE --- UPLOAD
                    // ????
                    
                    exec("rm /var/www/VMChecker/Queue/" . $submitUser . ".zip");
                    
                    $db   = new SQLite3('queue.db');
                    $date = strtotime("now");
                    $db->exec("INSERT INTO `queue`('id', 'date', 'user', 'email', 'status', 'localDir')
                    VALUES ('', '" . $date . "', '" . $submitUser . "', '" . $submitEmail . "', 'inQueue', '" . $localDirForDB . "')");
                }
            }
        }
    }
}

?>

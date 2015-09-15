<?php
/*
GitLab-ul va trimite un request de genul
hook.php?password=ceva + un post cu restul lucrurilor.
impreuna cu un $_POST care contine toate
datele despre event-ul pentru care facem listen
*/

require "Utils/Config.php";
require "Utils/MySQL.php";
require "Utils/Archiver.php";
require "Utils/DeleteDirectory.php";
require "Utils/DownloadDir.php";

if (isset($_GET['password'])) {
    $pass     = $_GET['password'];
    $clientip = $_SERVER['REMOTE_ADDR'];
    if (($pass == $token) && ($clientip == $gitlabIP)) {
        if (isset($_POST['ceva'])) {
            if (!empty($_POST['ceva'])) {
                $acelCeva = $_POST['ceva'];
                if (strpos($acelCeva, $keyWord) !== false) {
                    $localDir = $acelCeva; //Vom primi prin post repo-ul pe care trebuie sa il luam
                    
                    $ftp = new ftp($ftphost, $ftpuser, $ftppass);
                    $ftp->recursive($localDir, $remoteDir, 'get');
                    //Inserez in Baza de Date datele primite.
                    //Ma intereseaza: 
                    //$path(== $localDir), $user, $email, $date = strtime
                    $db       = new MySQL($dbName, $user, $pass, $host);
                    $date     = strtotime("now");
                    $inserObj = array(
                        'date' => $date,
                        'user' => $submitUser,
                        'email' => $submitEmail,
                        'status' => 'inQueue'
                    );
                    $db->Insert($insertObj, 'queue');
                    
                    /*
                    Trimiterea temei catre check
                    */
                    //Intai arhivez folderul cu tema
                    doZip($localDir, "/var/www/VMChecker/Queue/" . $submitUser . ".zip");
                    //Sterg continutul folderului
                    rrmdir($localDir);
                    
                    
                }
            }
        }
    }
}

?>

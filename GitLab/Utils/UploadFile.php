/*
local - ce sa ia
remote - unde sa salveze
host - host
user - user
pass - parola
port - 21 ftp, 22 sftp
*/
<?php
function uploadFile($localfile, $remoteFile, $host, $user, $pass, $port = 22)
{
          $connection = ssh2_connect($host, $port);
          ssh2_auth_password($connection, $user, $pass);
          $sftp = ssh2_sftp($connection);
 
          $stream = fopen("ssh2.sftp://$sftp$remoteFile", 'w');
          $file = file_get_contents($localFile);
          fwrite($stream, $file);
          fclose($stream);
}
?>
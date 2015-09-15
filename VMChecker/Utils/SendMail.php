<?php
function sendMailFileContents($fileLoc, $to, $from, $subj)
{
    
    $header = "From: " . $from . "\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: text/plain; charset=utf-8\r\n";
    $header .= "X-Priority: 1\r\n";
    mail($to, $subj, readFileForMail($fileLoc), $header);
}

function sendMail($to, $from, $subj, $body)
{
    $header = "From: " . $from . "\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: text/plain; charset=utf-8\r\n";
    $header .= "X-Priority: 1\r\n";
    mail($to, $subj, $body, $header);
}
?>
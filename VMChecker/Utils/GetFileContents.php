<?php
//Vom folosi ac. functie pentru a lua rezultatul obtinut pe tema
function getFileContents($fileLoc)
{
    $contents = file_get_content($fileLoc);
    if ($contents === false) {
        return "ERROR";
    } else {
        return $contents;
    }
}

function readFileForMail($fileLoc)
{
    $handle  = fopen($fileLoc, "r");
    $content = "";
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $content .= $line . "<br>";
        }
        fclose($handle);
        return $content;
    } else {
        return "ERROR";
    }
}
?>
/*
include_dir = true;

maindirectory
--- file 1
--- file 2
--- subdirectory 1
------ file 3
------ file 4
--- subdirectory 2
------ file 5
------ file 6

include_dir = false;
file 1
file 2
subdirectory 1
--- file 3
--- file 4
subdirectory 2
--- file 5
--- file 6
*/


function doZip($source, $destination, $include_dir = false)
{

    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    if (file_exists($destination)) { //Sterg fisierul .zip daca acesta exista deja
        unlink ($destination);
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }
    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        if ($include_dir) {

            $arr = explode("/",$source);
            $maindir = $arr[count($arr)- 1];

            $source = "";
            for ($i=0; $i < count($arr) - 1; $i++) { 
                $source .= '/' . $arr[$i];
            }

            $source = substr($source, 1);

            $zip->addEmptyDir($maindir);

        }

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
}
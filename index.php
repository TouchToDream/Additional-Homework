<?php
$ignoreFolders = ['folder 5', '.git'];


function scanDirectory($dir, $ignoreFolders)
{
    $result = [];
    $files = scandir($dir);
    foreach ($files as $value) {
        if ($value !== '.' && $value !== '..' && !in_array($value, $ignoreFolders)) {
            $path = $dir . '/' . $value;
            if (is_dir($path)) {
                $result[] = ['is_dir' => true, 'path' => $path, 'name' => $value];
                $result = array_merge($result, scanDirectory($path, $ignoreFolders));
            } else {
                $result[] = ['is_dir' => false, 'path' => $path, 'name' => $value];
            }
        }
    }
    return $result;
}


$results = scanDirectory('.', $ignoreFolders);
foreach ($results as $file) {
    echo $file['path'] . ' - ' . ($file['is_dir'] 
        ? '<span style="color:blue;">Directory</span>' 
        : '<span style="color:green;">File</span>') . '</br>';
}


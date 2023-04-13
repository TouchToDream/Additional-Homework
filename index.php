<?php

$ignoreFolders = ['folder 5', '.git'];

class Item
{
    public $path;
    public $name;
    public function __construct(string $path, string $name)
    {
        $this->path = $path;
        $this->name = $name;
    }
}

class File extends Item
{
    public function __construct(string $path, string $name)
    {
        parent::__construct($path, $name);
    }
}

class Folder extends Item
{
    public $is_dir;
    public function __construct(bool $is_dir, string $path, string $name)
    {
        parent::__construct($path, $name);
        $this->is_dir = $is_dir;
    }
}


function scanDirectory($dir, $ignoreFolders)
{
    $result = [];
    $files = scandir($dir);
    foreach ($files as $value) {
        if ($value !== '.' && $value !== '..' && !in_array($value, $ignoreFolders)) {
            $path = $dir . '/' . $value;
            if (is_dir($path)) {
                $result[] = new Folder(true, $path, $value);
                $result = array_merge($result, scanDirectory($path, $ignoreFolders));
            } else {
                $result[] = new File($path, $value);
            }
        }
    }
    return $result;
}


$results = scanDirectory('.', $ignoreFolders);
foreach ($results as $file) {
    echo $file->path . ' - ' . ($file->is_dir
        ? '<span style="color:blue;">Directory</span>'
        : '<span style="color:green;">File</span>') . '</br>';
}

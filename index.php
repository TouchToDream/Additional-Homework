<?php

$ignoreFolders = ['folder 5', '.git'];

interface Deletable
{
    public function delete();
}

abstract class Item
{
    public $path;
    public $name;

    public function __construct(string $path, string $name)
    {
        $this->path = $path;
        $this->name = $name;
    }
}

class File extends Item implements Deletable
{
    public function __construct(string $path, string $name)
    {
        parent::__construct($path, $name);
    }

    public function delete()
    {
    }
}

class Folder extends Item
{
    public function __construct(string $path, string $name)
    {
        parent::__construct($path, $name);
    }

    public static function scanDirectory(string $dir, array $ignoreFolders)
    {
        $result = [];
        $files = scandir($dir);
        foreach ($files as $value) {
            if ($value !== '.' && $value !== '..' && !in_array($value, $ignoreFolders)) {
                $path = $dir . '/' . $value;
                if (is_dir($path)) {
                    $result[] = new Folder($path, $value);
                    $result = array_merge($result, self::scanDirectory($path, $ignoreFolders));
                } else {
                    $result[] = new File($path, $value);
                }
            }
        }
        return $result;
    }
}

$results = Folder::scanDirectory('.', $ignoreFolders);

foreach ($results as $file) {
    $objectName = get_class($file);
    echo $file->path . ' - ' . ($objectName === 'Folder'
        ? '<span style="color:blue;">Directory</span>'
        : '<span style="color:green;">File</span>') . '</br>';
}

echo '<pre>';
var_dump($results);
echo '</pre>';

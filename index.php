<?php
$ignoreFolders = ['folder 5', '.git'];

class Item
{
    public $is_dir;
    public $path;
    public $name;

    public function __construct(bool $is_dir, string $path, string $name)
    {
        $this->is_dir = $is_dir;
        $this->path = $path;
        $this->name = $name;
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
                $result[] = new Item(true, $path, $value);
                $result = array_merge($result, scanDirectory($path, $ignoreFolders));
            } else {
                $result[] = new Item(false, $path, $value);
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

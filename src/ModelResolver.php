<?php

namespace Advaith\SeamlessAdmin;

use FilesystemIterator;
use Illuminate\Support\Facades\DB;

class ModelResolver
{
    private array $models = [];

    public function __construct()
    {
        $this->registerModels(app_path('models/'));
    }

    private function extract_classname($file): string
    {
        $fp = fopen($file, 'r');
        $class = $namespace = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) break;

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (!str_contains($buffer, '{')) continue;

            for (; $i < count($tokens); $i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if ($tokens[$j][0] === T_NAME_QUALIFIED) $namespace .= '\\' . $tokens[$j][1];
                        else if ($tokens[$j] === '{' || $tokens[$j] === ';') break;
                    }
                }

                if ($tokens[$i][0] === T_CLASS)
                    for ($j = $i + 1; $j < count($tokens); $j++)
                        if ($tokens[$j] === '{')
                            $class = $tokens[$i + 2][1];
            }
        }

        return "{$namespace}\\{$class}";
    }

    private function registerModels(string $path): void
    {
        // iterator
        $fileSystemIterator = new FilesystemIterator($path);

        // if the trait is registered in a particular model, add it to the models array
        foreach ($fileSystemIterator as $file) {
            // recursive call if the file is a directory
            if ($file->isDir()) {
                $this->registerModels($file->getPathname());
                continue;
            }

            $className = $this->extract_classname($file->getPathname());

            if (property_exists($className, 'hasAdminPage'))
                $this->models[] = $className;
        }
    }

    // function to parse the type into a md5 string
    public function parseType(string $type): string
    {
        return md5($type);
    }

    // function resolve the type from md5 string
    public function resolveType(string $type): string|null
    {
        return collect($this->models)
            ->filter(fn($model) => md5($model) == $type)
            ->first();
    }

    // function to get column information from the table
    public function getColumns(string $type): array
    {
        $table = (new $type)->getTable();
        return DB::select("SHOW COLUMNS FROM $table WHERE type != 'timestamp' AND extra != 'auto_increment'");
    }

    // get the models registered
    public function getModels(): array
    {
        return $this->models;
    }
}

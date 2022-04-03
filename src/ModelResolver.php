<?php

namespace Advaith\SeamlessAdmin;

use FilesystemIterator;

class ModelResolver
{
    // TODO: Cache the results of models after generating it once
    public array $models = [];

    public function __construct()
    {
        $this->registerModels();
    }

    private function extract_namespace($file): string
    {
        $ns = NULL;
        $handle = fopen($file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (str_starts_with($line, 'namespace')) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');
                    break;
                }
            }

            fclose($handle);
        }

        return $ns;
    }

    private function registerModels(): void
    {
        // iterator
        $fileSystemIterator = new FilesystemIterator(app_path('models/'));

        // if the trait is registered in a particular model, add it to the models array
        foreach ($fileSystemIterator as $file) {
            $namespace = $this->extract_namespace($file->getPathname());
            $basename = pathinfo($file->getBasename(), PATHINFO_FILENAME);
            $className = "$namespace\\$basename";

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
    public function resolveType(string $type): string | null
    {
        return collect($this->models)
            ->filter(fn ($model) => md5($model) == $type)
            ->first();
    }
}

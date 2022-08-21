<?php

namespace Advaith\SeamlessAdmin;

use Advaith\SeamlessAdmin\Facades\SeamlessAdmin;
use FilesystemIterator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ModelResolver
{
    private array $models = [];

    public function __construct()
    {
        $this->registerModels(app_path('Models/'));
    }

    private function registerModels(string $path): void
    {
        // caching the models
        $this->models = Cache::rememberForever($this->getCacheKey(), function () use ($path) {
            $models = [];

            // file system iterator
            $fileSystemIterator = new FilesystemIterator($path);

            // if the trait is registered in a particular model, add it to the models array
            foreach ($fileSystemIterator as $file) {
                // recursive call if the file is a directory
                if ($file->isDir()) {
                    $this->registerModels($file->getPathname());
                    continue;
                }

                // only parse .php files
                if ($file->getExtension() !== 'php') continue;

                $className = $this->extract_classname($file->getPathname());

                // ignore if the file is not a class
                try {
                    new $className;
                } catch (\Exception) {
                    continue;
                }

                if (in_array(
                    Traits\SeamlessAdmin::class,
                    class_uses_recursive($className),
                    true
                )) $models[] = $className;
            }

            return $models;
        });
    }

    public function getCacheKey(): string
    {
        return 'seamless-admin.models';
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

    /**
     * function to parse the type into a md5 string
     *
     * @param string $type
     * @return string
     */
    public function parseType(string $type): string
    {
        return md5($type);
    }

    /**
     * function resolve the type from md5 string
     *
     * @param string $type
     * @return string|null
     */
    public function resolveType(string $type): string|null
    {
        return collect($this->models)
            ->filter(fn($model) => md5($model) == $type)
            ->first();
    }

    /**
     * resolve model from table name if exists
     *
     * @param string $table
     * @return string|null
     */
    public function resolveModel(string $table): string|null
    {
        return collect($this->models)
            ->filter(fn($model) => (new $model)->getTable() === $table)
            ->first();
    }

    /**
     * get the registered models
     *
     * @return array
     */
    public function getSidebarElements(): array
    {
        $models = $this->getModels();
        $routes = SeamlessAdmin::getRoutes();

        $combined = ['_default' => []];

        foreach ($models as $model) {
            if (($group = (new $model)->adminGroup) !== null) $combined[$group][] = $model;
            else $combined['_default'][] = $model;
        }

        foreach ($routes as $route) {
            if (isset($route['options']['group'])) $combined[$route['options']['group']][] = $route;
            else $combined['_default'][] = $route;
        }

        return [$combined, count($models) + count($routes)];
    }

    /**
     * @return array
     */
    public function getModels(): array
    {
        return array_filter(
            $this->models,
            function ($model) {
                $instance = new $model;
                return $instance->hasAdminPage !== false && $instance->adminCanAccessIndex();
            }
        );
    }

    /**
     * function to get column information from the table
     *
     * @param string $type
     * @return array
     */
    public function getColumns(string $type): array
    {
        $table = (new $type)->getTable();
        return DB::select("
            SHOW COLUMNS FROM {$table}
            WHERE (
                type != 'timestamp' AND
                extra != 'auto_increment'
            )
        ");
    }

    /**
     * get foreign key constrains in a table
     *
     * @param string $type
     * @return array
     */
    public function foreign_keys(string $type): array
    {
        return DB::select(
            'SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE (
                    REFERENCED_TABLE_SCHEMA = ? AND
                    TABLE_NAME = ?
            )',
            [
                DB::connection()->getDatabaseName(),
                (new $type)->getTable()
            ]
        );
    }
}

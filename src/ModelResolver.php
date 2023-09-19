<?php

namespace Advaith\SeamlessAdmin;

use Advaith\SeamlessAdmin\Exceptions\UnhandledDatabaseConnection;
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

    private function extractFromFolder(string $path): array
    {
        $models = [];

        // file system iterator
        $fileSystemIterator = new FilesystemIterator($path);

        // if the trait is registered in a particular model, add it to the models array
        foreach ($fileSystemIterator as $file) {
            // recursive call if the file is a directory
            if ($file->isDir()) {
                $models = array_merge($models, $this->extractFromFolder($file->getPathname()));
                continue;
            }

            // only parse .php files
            if ($file->getExtension() !== 'php') continue;

            $className = $this->extract_classname($file->getPathname());

            // ignore if the file is not a class
            if (!class_exists($className)) continue;

            if (in_array(
                Traits\SeamlessAdmin::class,
                class_uses_recursive($className),
                true
            )) $models[] = $className;
        }

        return $models;
    }

    private function registerModels(string $path): void
    {
        // caching the models
        $this->models = Cache::remember(
            $this->getCacheKey(),
            now()->addDay(),
            fn () => $this->extractFromFolder($path)
        );
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
            ->filter(fn ($model) => md5($model) == $type)
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
            ->filter(fn ($model) => (new $model)->getTable() === $table)
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
        // get the database connection type
        $conn = config('database.default');

        // get the table name
        $table = (new $type)->getTable();

        // prepare the SQL query based on the connection type
        if ($conn == 'mysql') {
            $query = "
                SELECT 
                    COLUMN_NAME AS 'field', 
                    COLUMN_TYPE AS 'type', 
                    IS_NULLABLE AS 'is_null' 
                FROM 
                    INFORMATION_SCHEMA.COLUMNS 
                WHERE 
                    TABLE_NAME = '$table' AND
                    TABLE_SCHEMA = DATABASE() AND
                    DATA_TYPE != 'timestamp' AND 
                    EXTRA != 'auto_increment'
            ";
        } else if ($conn == 'pgsql') {
            $query = "
                SELECT
                    data_type AS 'type', 
                    column_name AS 'field', 
                    is_nullable AS 'is_null'
                FROM
                    information_schema.columns
                WHERE
                    table_schema = 'public'
                    AND table_name = '$table';";
        } else if ($conn == 'sqlite') {
            $results = DB::select("PRAGMA table_info($table);");
            $query = [];

            // sqlite doesn't support aliasing in PRAGMA queries
            foreach ($results as $row) {
                $query[] = (object) [
                    'field' => $row->name,
                    'type' => $row->type,
                    'is_null' => !$row->notnull,
                ];
            }

            return $query;
        } else {
            throw new UnhandledDatabaseConnection("The database connection '{$conn}' is not currently supported by this package. Please use a supported database connection.");
        }

        // execute the query and return the result as an array of objects
        return DB::select($query);
    }

    /**
     * get foreign key constrains in a table
     *
     * @param string $type
     * @return array
     */
    public function foreign_keys(string $type): array
    {
        // get the database connection type
        $conn = config('database.default');
        
        // get the table name
        $table = (new $type)->getTable();

        if ($conn == 'mysql') {
            return DB::select(
                'SELECT column_name, referenced_table_name, referenced_column_name
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE (
                    REFERENCED_TABLE_SCHEMA = ? AND
                    TABLE_NAME = ?
                )',
                [
                    DB::connection()->getDatabaseName(),
                    $table
                ]
            );
        } else if ($conn == 'pgsql') {
            return DB::select("
                SELECT
                    kcu.column_name,
                    ccu.column_name AS referenced_column_name,
                    ccu.table_name AS referenced_table_name
                FROM information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                ON 
                    tc.constraint_name = kcu.constraint_name
                    AND tc.table_schema = kcu.table_schema
                JOIN information_schema.constraint_column_usage AS ccu
                ON ccu.constraint_name = tc.constraint_name
                WHERE tc.constraint_type = 'FOREIGN KEY' AND tc.table_name = '$table';
            ");
        } else if ($conn == 'sqlite') {
            return array_map(
                fn ($item) => (object) [ 
                    'column_name' => $item->from,
                    'referenced_table_name' => $item->table,
                    'referenced_column_name' => $item->to,
                ],
                DB::select("PRAGMA foreign_key_list($table);")
            );
        } else {
            throw new UnhandledDatabaseConnection("The database connection '$conn' is not currently supported by this package. Please use a supported database connection.");
        }
    }
}

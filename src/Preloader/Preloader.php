<?php

namespace Utopia\Preloader;

class Preloader
{
    /**
     * @var array<string, mixed>
     */
    protected $ignores = [];

    /**
     * @var array<string, mixed>
     */
    protected $paths = [];

    /**
     * @var array<string, mixed>
     */
    protected $loaded = [];

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var array<string, mixed>
     */
    protected $included = [];

    /**
     * Constructor
     * 
     * @param string $paths
     */
    public function __construct(string ...$paths)
    {
        $this->paths = $paths;

        $require = __DIR__.'/../../../../composer/autoload_classmap.php';
        $classMap = (file_exists($require)) ? require $require : [];

        $this->paths = \array_merge(
            $this->paths,
            \array_values($classMap)
        );
    }
    
    /**
     * Paths
     * 
     * Path to load
     * 
     * @param string $paths
     * 
     * @return $this
     */
    public function paths(string ...$paths): self
    {
        $this->paths = \array_merge(
            $this->paths,
            $paths
        );

        return $this;
    }

    /**
     * Ignore
     * 
     * Ignore a given path or file
     * 
     * @param string $names
     * 
     * @return $this
     */
    public function ignore(string ...$names): self
    {
        foreach ($names as $name) {
            if (is_readable($name)) {
                $this->ignores[] = $name;
            } else {
                if ($this->debug) {
                    echo "[Preloader] Failed to ignore path `{$name}`".PHP_EOL;
                }
            }
        }

        return $this;
    }

    /**
     * Load
     * 
     * Loads all preloader preconfigured paths and files
     */
    public function load(): void
    {
        $this->loaded = get_included_files();

        foreach ($this->paths as $path) {
            $this->loadPath(\rtrim($path, '/'));
        }

        if ($this->debug) {
            echo "[Preloader] Preloaded {$this->count} files.".PHP_EOL;
        }
    }

    /**
     * Get Count
     * 
     * Get the total number of loaded files.
     * 
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Get List
     * 
     * Get a list of all included paths.
     * 
     * @return array
     */
    public function getList(): array
    {
        return $this->included;
    }

    /**
     * Set Debug
     * 
     * Set debug mode status.
     * 
     * @param bool $status
     */
    public function setDebug(bool $status = true): self
    {
        $this->debug = $status;
        return $this;
    }

    /**
     * Load Path
     * 
     * Load a specific file or folder and nested folders.
     * 
     * @param string $path
     * @return void
     */
    private function loadPath(string $path): void
    {
        if (\is_dir($path)) {
            $this->loadDir($path);

            return;
        }

        $this->loadFile($path);
    }


    /**
     * Load Directory
     * 
     * Load a specific folder and nested folders.
     * 
     * @param string $path
     * @return void
     */
    private function loadDir(string $path): void
    {
        $handle = \opendir($path);

        while ($file = \readdir($handle)) {
            if (\in_array($file, ['.', '..'])) {
                continue;
            }

            $this->loadPath("{$path}/{$file}");
        }

        \closedir($handle);
    }

    /**
     * Load File
     * 
     * Load a specific file.
     * 
     * @param string $path
     * @return void
     */
    private function loadFile(string $path): void
    {
        if ($this->shouldIgnore($path)) {
            return;
        }
        
        if (\in_array(\realpath($path), $this->included)) {
            if ($this->debug) {
                echo "[Preloader] Skiped `{$path}`".PHP_EOL;
            }
            return;
        }
        
        if (\in_array(\realpath($path), $this->loaded)) {
            if ($this->debug) {
                echo "[Preloader] Skiped `{$path}`".PHP_EOL;
            }
            return;
        }
        
        try {
            // opcache_compile_file($path);
            require $path;
        } catch (\Throwable $th) {
            if ($this->debug) {
                echo "[Preloader] Failed to load `{$path}`: ".$th->getMessage().PHP_EOL;
            }
            return;
        }

        $this->loaded = get_included_files();
        $this->included[] = $path;
        $this->count++;
    }

    /**
     * Should Ignore
     * 
     * Should a given path be ignored or not?
     * 
     * @param string $path
     * @return bool
     */
    private function shouldIgnore(?string $path): bool
    {
        if ($path === null) {
            return true;
        }

        if (!\in_array(\pathinfo($path, PATHINFO_EXTENSION), ['php'])) {
            return true;
        }

        foreach ($this->ignores as $ignore) {
            if (\strpos($path, $ignore) === 0) {
                return true;
            }
        }

        return false;
    }
}
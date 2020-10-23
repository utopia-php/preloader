<?php

namespace Utopia\Preloader;

class Preloader
{
    /**
     * @var array
     */
    protected $ignores = [];

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var array
     */
    protected $included = [];

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
    
    public function paths(string ...$paths): self
    {
        $this->paths = \array_merge(
            $this->paths,
            $paths
        );

        return $this;
    }

    public function ignore(string ...$names): self
    {
        foreach ($names as $name) {
            if (is_readable($name)) {
                $this->ignores[] = $name;
            } else {
                echo "[Preloader] Failed to ignore path `{$name}`".PHP_EOL;
            }
        }

        return $this;
    }

    public function load(): void
    {
        $this->included = get_included_files();

        foreach ($this->paths as $path) {
            $this->loadPath(\rtrim($path, '/'));
        }

        $already = count($this->included);

        $this->count = $already;

        //echo "[Preloader] Preloaded {$already} files.".PHP_EOL;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    private function loadPath(string $path): void
    {
        if (\is_dir($path)) {
            $this->loadDir($path);

            return;
        }

        $this->loadFile($path);
    }

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

    private function loadFile(string $path): void
    {
        if ($this->shouldIgnore($path)) {
            return;
        }
        
        if (\in_array(\realpath($path), $this->included)) {
            // echo "[Preloader] Skiped `{$path}`".PHP_EOL;
            return;
        }
        
        // echo "[Preloader] Preloaded `{$path}`".PHP_EOL;

        try {
            // opcache_compile_file($path);
            require $path;
        } catch (\Throwable $th) {
            echo "[Preloader] Failed to load `{$path}`: ".$th->getMessage().PHP_EOL;
            return;
        }

        $this->included = array_merge(get_included_files(), [realpath($path)]);
    }

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
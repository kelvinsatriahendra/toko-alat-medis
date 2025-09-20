<?php
// Lightweight .env loader (no external dependency)
// Usage: require_once __DIR__ . '/env.php'; loadEnv(dirname(__DIR__));
if (!function_exists('loadEnv')) {
    function loadEnv(string $basePath, string $file = '.env'): void
    {
        $envFile = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
        if (!is_readable($envFile)) {
            return; // silently ignore if not found
        }
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || str_starts_with($line, '//')) {
                continue;
            }
            // Support KEY="value with #" and KEY='value', and KEY=value
            if (!str_contains($line, '=')) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                $value = substr($value, 1, -1);
            }
            // Expand simple ${VAR} references if present
            $value = preg_replace_callback('/\$\{([A-Z0-9_]+)\}/i', function ($m) {
                $key = $m[1];
                return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: '';
            }, $value);

            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            putenv("{$name}={$value}");
        }
    }
}

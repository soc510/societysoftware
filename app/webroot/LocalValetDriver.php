<?php

use Valet\Drivers\BasicValetDriver;

/**
 * Local-only driver so Herd/Valet's nginx routes CakePHP 2 pretty URLs.
 *
 * BasicValetDriver 404s multi-segment URIs like /users/login because no such
 * file exists on disk. CakePHP needs every non-asset request handed to
 * webroot/index.php instead - the same thing the Apache .htaccess does in
 * production. This file is ignored outside Herd/Valet.
 */

// On the live servers Valet does not exist, so requesting this file directly
// would fatal on the missing parent class and leak the path in the response.
// Bail out instead - Valet has the class loaded by the time it scans drivers.
if (!class_exists(BasicValetDriver::class, false)) {
    return;
}

class LocalValetDriver extends BasicValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return true;
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        // Never hand a .php file to nginx as a static asset - that would leak source.
        if (strtolower((string)pathinfo($uri, PATHINFO_EXTENSION)) === 'php') {
            return false;
        }

        if ($this->isActualFile($staticFilePath = $sitePath . $uri)) {
            return $staticFilePath;
        }

        return false;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        // Standalone entry points (adminer.php etc.) still execute directly.
        if (strtolower((string)pathinfo($uri, PATHINFO_EXTENSION)) === 'php'
            && $this->isActualFile($sitePath . $uri)) {
            return $sitePath . $uri;
        }

        return $sitePath . '/index.php';
    }
}

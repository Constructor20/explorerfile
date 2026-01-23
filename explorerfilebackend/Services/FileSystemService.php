<?php

require_once __DIR__ . '/../Config/paths.php';

class FileSystemService
{
    private string $baseDir;

    public function __construct(string $baseDir = BASE_DIR)
    {
        $this->baseDir = rtrim($baseDir, '/');
    }

    public function buildTree(?string $dir = null): array
    {
        $directory = $dir ?? $this->baseDir;

        if (!is_dir($directory)) {
            error_log('Directory does not exist: ' . $directory);
            return [];
        }

        return $this->buildTreeRecursive($directory, '');
    }

    private function buildTreeRecursive(string $dir, string $baseRelPath): array
    {
        $tree = [];

        $files = scandir($dir);
        if ($files === false) {
            error_log('Failed to scan directory: ' . $dir);
            return $tree;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($path)) {
                $folderName = $file . '/';
                $tree[$folderName] = $this->buildTreeRecursive($path, $baseRelPath . $folderName);
            } else {
                $tree[$file] = $file;
            }
        }

        return $tree;
    }

    public function buildTreeWithLeadingSlash(?string $dir = null): array
    {
        $directory = $dir ?? $this->baseDir;

        if (!is_dir($directory)) {
            error_log('Directory does not exist: ' . $directory);
            return [];
        }

        return $this->buildTreeRecursiveWithSlash($directory, '');
    }

    private function buildTreeRecursiveWithSlash(string $dir, string $baseRelPath): array
    {
        $tree = [];

        $files = scandir($dir);
        if ($files === false) {
            error_log('Failed to scan directory: ' . $dir);
            return $tree;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($path)) {
                $folderPath = '/' . ltrim($baseRelPath . $file, '/') . '/';
                $tree[$folderPath] = $this->buildTreeRecursiveWithSlash($path, $baseRelPath . $file . '/');
            } else {
                $fullPath = '/' . ltrim($baseRelPath . $file, '/');
                $tree[$fullPath] = $fullPath;
            }
        }

        return $tree;
    }

    public function getRelativePath(string $fullPath): string
    {
        $fullPath = rtrim($fullPath, '/');
        $baseDir = rtrim($this->baseDir, '/');

        if (!str_starts_with($fullPath, $baseDir)) {
            return '';
        }

        $relative = substr($fullPath, strlen($baseDir));
        return '/' . ltrim($relative, '/');
    }

    public function getAllItemsInFolder(array $tree, string $folderPath): array
    {
        $items = [];
        $this->searchInTree($tree, '', $folderPath, $items);
        return array_filter($items, fn($item) => !empty(trim($item)));
    }

    private function searchInTree(array $subtree, string $currentPath, string $targetFolder, array &$items): void
    {
        foreach ($subtree as $key => $value) {
            if ($key === '.' || $key === '..' || empty($key)) {
                continue;
            }

            $fullPath = $currentPath . $key;
            $isFolder = str_ends_with($key, '/');

            if ($isFolder) {
                if ($targetFolder === '' || str_starts_with($fullPath, $targetFolder)) {
                    $items[] = $fullPath;
                    $this->searchInTree($value, $fullPath, $targetFolder, $items);
                }
            } else {
                if ($targetFolder === '' || str_starts_with($fullPath, $targetFolder)) {
                    $items[] = $fullPath;
                }
            }
        }
    }

    public function directoryExists(string $dir): bool
    {
        return is_dir($dir);
    }

    public function getBaseDir(): string
    {
        return $this->baseDir;
    }
}

<?php

require_once __DIR__ . '/../Repositories/PermissionRepository.php';
require_once __DIR__ . '/../Models/Permission.php';

class PermissionService
{
    private PermissionRepository $repository;
    private array $availablePaths;

    public function __construct(PDO $db, array $availablePaths = [])
    {
        $this->repository = new PermissionRepository($db);
        $this->availablePaths = $availablePaths;
    }

    public function getExistingPermissions(int $userId): Permission
    {
        $permission = $this->repository->findByUserId($userId);
        return $permission ?? new Permission($userId, []);
    }

    public function savePermissions(Permission $permission): bool
    {
        return $this->repository->save($permission);
    }

    public function filterAvailablePaths(array $paths): array
    {
        return array_filter($paths, fn($path) => in_array($path, $this->availablePaths, true));
    }

    public function pathsToTree(array $paths): array
    {
        $tree = [];

        foreach ($paths as $path) {
            $path = ltrim($path, '/');
            if (empty(trim($path))) {
                continue;
            }

            $isFolder = str_ends_with($path, '/');
            $parts = array_filter(explode('/', $path), fn($part) => !empty(trim($part)));

            if (empty($parts)) {
                continue;
            }

            $current = &$tree;
            $totalParts = count($parts);

            foreach ($parts as $index => $part) {
                $isLast = $index === $totalParts - 1;

                if ($isFolder && $isLast) {
                    $key = $part . '/';
                    if (!isset($current[$key])) {
                        $current[$key] = [];
                    }
                } elseif ($isLast) {
                    $current[$part] = $part;
                } else {
                    $key = $part . '/';
                    if (!isset($current[$key])) {
                        $current[$key] = [];
                    }
                    $current = &$current[$key];
                }
            }
        }

        return $tree;
    }

    public function cleanupEmptyParentFolders(array $paths, string $removedPath): array
    {
        $result = $paths;
        $parts = array_filter(explode('/', $removedPath), fn($p) => !empty(trim($p)));

        $currentPath = '';
        foreach ($parts as $part) {
            $currentPath .= $part . '/';

            $index = array_search($currentPath, $result, true);
            if ($index !== false && !$this->hasSelectedDescendants($result, $currentPath)) {
                unset($result[$index]);
                $result = array_values($result);
            } else {
                break;
            }
        }

        return $result;
    }

    private function hasSelectedDescendants(array $paths, string $folderPath): bool
    {
        $prefix = rtrim($folderPath, '/') . '/';
        foreach ($paths as $path) {
            if ($path !== $folderPath && str_starts_with($path, $prefix)) {
                return true;
            }
        }
        return false;
    }

    public function countVisibleItems(array $tree, string $parentPath, array $selectedPermissions): int
    {
        $count = 0;

        foreach ($tree as $key => $value) {
            if (empty($key) || $key === '.' || $key === '..') {
                continue;
            }

            $fullPath = $parentPath . $key;

            if (empty(trim($fullPath))) {
                continue;
            }

            if (in_array($fullPath, $selectedPermissions, true)) {
                continue;
            }

            if ($this->hasSelectedParent($selectedPermissions, $parentPath)) {
                continue;
            }

            if (str_ends_with($key, '/')) {
                $count += $this->countVisibleItems($value, $fullPath, $selectedPermissions);
            } else {
                $count++;
            }
        }

        return $count;
    }

    private function hasSelectedParent(array $selectedPermissions, string $path): bool
    {
        $checkPath = rtrim($path, '/') . '/';
        while (!empty($checkPath) && $checkPath !== '/') {
            if (in_array($checkPath, $selectedPermissions, true)) {
                return true;
            }
            $lastSlash = strrpos(substr($checkPath, 0, -1), '/');
            $checkPath = $lastSlash !== false
                ? substr($checkPath, 0, $lastSlash + 1)
                : '';
        }
        return false;
    }
}

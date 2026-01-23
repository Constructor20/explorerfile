<?php

class Permission
{
    private int $userId;
    private array $paths;

    public function __construct(int $userId, array $paths = [])
    {
        $this->userId = $userId;
        $this->paths = array_filter($paths, fn($path) => $this->isValidPath($path));
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    public function addPath(string $path): void
    {
        if ($this->isValidPath($path) && !in_array($path, $this->paths, true)) {
            $this->paths[] = $path;
        }
    }

    public function removePath(string $path): void
    {
        $this->paths = array_values(array_filter(
            $this->paths,
            fn($p) => $p !== $path && !$this->isDescendantOf($p, $path)
        ));
    }

    public function hasPath(string $path): bool
    {
        return in_array($path, $this->paths, true);
    }

    public function hasDescendantSelected(string $folderPath): bool
    {
        $prefix = rtrim($folderPath, '/') . '/';
        foreach ($this->paths as $path) {
            if ($path !== $folderPath && str_starts_with($path, $prefix)) {
                return true;
            }
        }
        return false;
    }

    public function toJson(): string
    {
        return json_encode(['paths' => $this->paths], JSON_THROW_ON_ERROR);
    }

    public static function fromJson(int $userId, string $json): self
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        return new self($userId, $data['paths'] ?? []);
    }

    public function toDatabaseJson(): string
    {
        return $this->toJson();
    }

    public static function fromDatabaseJson(int $userId, string $json): ?self
    {
        try {
            return self::fromJson($userId, $json);
        } catch (JsonException $e) {
            error_log('Invalid JSON in database: ' . $e->getMessage());
            return null;
        }
    }

    private function isValidPath(string $path): bool
    {
        return !empty(trim($path)) && str_starts_with($path, '/');
    }

    private function isDescendantOf(string $child, string $parent): bool
    {
        $parentPrefix = rtrim($parent, '/') . '/';
        return str_starts_with($child, $parentPrefix) && $child !== $parent;
    }

    public function count(): int
    {
        return count($this->paths);
    }
}

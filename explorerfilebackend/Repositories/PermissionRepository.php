<?php

require_once __DIR__ . '/../Models/Permission.php';

class PermissionRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByUserId(int $userId): ?Permission
    {
        $sql = "SELECT encodedjson FROM permission WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $json = $stmt->fetchColumn();
        if (!$json) {
            return null;
        }

        return Permission::fromDatabaseJson($userId, $json);
    }

    public function save(Permission $permission): bool
    {
        $userId = $permission->getUserId();
        $json = $permission->toDatabaseJson();

        if ($this->exists($userId)) {
            return $this->update($userId, $json);
        }

        return $this->insert($userId, $json);
    }

    public function exists(int $userId): bool
    {
        $sql = "SELECT COUNT(*) FROM permission WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    private function update(int $userId, string $json): bool
    {
        $sql = "UPDATE permission SET encodedjson = :json WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':json', $json, PDO::PARAM_STR);

        return $stmt->execute();
    }

    private function insert(int $userId, string $json): bool
    {
        $sql = "INSERT INTO permission (user_id, encodedjson) VALUES (:id, :json)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':json', $json, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete(int $userId): bool
    {
        $sql = "DELETE FROM permission WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

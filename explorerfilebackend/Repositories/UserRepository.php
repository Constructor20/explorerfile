<?php

require_once __DIR__ . '/../Models/User.php';

class UserRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?User
    {
        $sql = "SELECT * FROM userdata WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? User::fromDatabaseRow($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM userdata WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? User::fromDatabaseRow($row) : null;
    }

    public function findByUsername(string $username): ?User
    {
        $sql = "SELECT * FROM userdata WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? User::fromDatabaseRow($row) : null;
    }

    public function findByEmailOrUsername(string $identifier): ?User
    {
        $sql = "SELECT * FROM userdata WHERE email = :id OR username = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $identifier, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? User::fromDatabaseRow($row) : null;
    }

    public function emailExists(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM userdata WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    public function usernameExists(string $username): bool
    {
        $sql = "SELECT COUNT(*) FROM userdata WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(User $user): bool
    {
        $sql = "INSERT INTO userdata (username, email, password, isadmin) VALUES (:username, :email, :password, :isadmin)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $user->getUsername(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(':isadmin', $user->isAdmin() ? 1 : 0, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

<?php

class User
{
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private int $isadmin;

    public function __construct(
        string $username,
        string $email,
        string $password,
        int $isadmin = 0,
        ?int $id = null
    ) {
        $this->id = $id ?? 0;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->isadmin = $isadmin;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isAdmin(): bool
    {
        return $this->isadmin === 1;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            $row['username'],
            $row['email'],
            $row['password'],
            (int) $row['isadmin'],
            (int) $row['id']
        );
    }

    public static function forRegistration(string $username, string $email, string $password): self
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return new self($username, $email, $hashedPassword, 0);
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}

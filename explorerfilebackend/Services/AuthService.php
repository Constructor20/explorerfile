<?php

require_once __DIR__ . '/../Repositories/UserRepository.php';
require_once __DIR__ . '/../Models/User.php';

class AuthService
{
    private UserRepository $userRepository;

    public function __construct(PDO $db)
    {
        $this->userRepository = new UserRepository($db);
    }

    public function register(string $email, string $username, string $password, string $confirmPassword): array
    {
        $errors = [];

        if (empty(trim($email))) {
            $errors[] = 'email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'invalid_email';
        } elseif ($this->userRepository->emailExists($email)) {
            $errors[] = 'email';
        }

        if (empty(trim($username))) {
            $errors[] = 'username';
        } elseif ($this->userRepository->usernameExists($username)) {
            $errors[] = 'username';
        }

        if (empty(trim($password))) {
            $errors[] = 'password';
        } elseif (!preg_match('/^[A-Za-z0-9]+$/', $password)) {
            $errors[] = 'invalid_password';
        }

        if (empty(trim($confirmPassword))) {
            $errors[] = 'confirm_password';
        } elseif ($password !== $confirmPassword) {
            $errors[] = 'nonesamepassword';
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'error' => $errors[0]
            ];
        }

        $user = User::forRegistration(trim($username), trim($email), trim($password));
        $success = $this->userRepository->create($user);

        if ($success) {
            return ['success' => true];
        }

        return ['success' => false, 'error' => 'database'];
    }

    public function login(string $identifier, string $password): array
    {
        if (empty(trim($identifier))) {
            return ['success' => false, 'error' => 'emptyid', 'user' => null];
        }

        if (empty(trim($password))) {
            return ['success' => false, 'error' => 'emptypassword', 'user' => null];
        }

        $user = $this->userRepository->findByEmailOrUsername($identifier);

        if (!$user) {
            return ['success' => false, 'error' => 'emptyid', 'user' => null];
        }

        if (!$user->verifyPassword($password)) {
            return ['success' => false, 'error' => 'emptypassword', 'user' => null];
        }

        return ['success' => true, 'user' => $user];
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }
}

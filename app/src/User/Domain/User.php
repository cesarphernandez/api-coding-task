<?php

namespace App\User\Domain;

class User
{

    public function __construct(
        private string $email,
        private string $password,
        private array $roles
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function hasRoles(array $roles): bool
    {
        return count(array_intersect($roles, $this->roles)) > 0;
    }

    public function authenticate(string $password): bool
    {
        return md5($password) === $this->password;
    }

}
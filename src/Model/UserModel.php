<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\Model;
use PDO;

class UserModel extends Model
{
    protected string $table = 'users';


    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(string $name, string $email, string $passwordHash): int|false
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, email, password) VALUES (:name, :email, :password)");
        $result = $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash
        ]);

        return $result ? (int)$this->db->lastInsertId() : false;
    }
}

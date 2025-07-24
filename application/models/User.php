<?php

namespace Application\Model;

class User extends Model
{
    protected $table = 'users';

    // گرفتن همه کاربران
    public function getAllUsers()
    {
        $sql = "SELECT id, username, email, created_at FROM {$this->table}";
        return $this->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    // گرفتن کاربر با شناسه
    public function getUserById($id)
    {
        $sql = "SELECT id, username, email, created_at FROM {$this->table} WHERE id = :id";
        return $this->query($sql, ['id' => $id])->fetch(\PDO::FETCH_ASSOC);
    }

    // ایجاد کاربر جدید
    public function createUser($data)
    {
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            throw new \InvalidArgumentException('All fields are required');
        }

        $sql = "INSERT INTO {$this->table} (username, email, password) 
                VALUES (:username, :email, :password)";

        return $this->execute($sql, [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
    }
    public function create(array $data)
    {
        if (
            !isset($data['username']) || empty($data['username']) ||
            !isset($data['email']) || empty($data['email']) ||
            !isset($data['password']) || empty($data['password'])
        ) {
            throw new \InvalidArgumentException('All fields are required');
        }

        $sql = "INSERT INTO {$this->table} (username, email, password) 
            VALUES (:username, :email, :password)";

        // رمز عبور اینجا هش می‌شود (فقط یک بار)
        return $this->execute($sql, [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
    }

    // به‌روزرسانی اطلاعات کاربر
    public function updateUser($id, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET username = :username, email = :email, updated_at = NOW()
                WHERE id = :id";

        return $this->execute($sql, [
            'username' => $data['username'],
            'email' => $data['email'],
            'id' => $id,
        ]);
    }



    // حذف کاربر
    public function deleteUser($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }

    // پیدا کردن کاربر با username
    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        return $this->query($sql, ['username' => $username])->fetch(\PDO::FETCH_ASSOC);
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        return $this->query($sql, ['email' => $email])->fetch(\PDO::FETCH_ASSOC);
    }
    // چک کردن وجود ایمیل
    public function existsByEmail($email)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        return $this->query($sql, ['email' => $email])->fetchColumn() > 0;
    }

    // چک کردن وجود username
    public function existsByUsername($username)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = :username";
        return $this->query($sql, ['username' => $username])->fetchColumn() > 0;
    }
}

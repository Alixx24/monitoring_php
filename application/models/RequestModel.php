<?php

namespace Application\Model;

class RequestModel extends Model
{

    protected $table = 'requests';

    public function getAllRequests()
    {
        $sql = "SELECT id, url, name, email, time,created_at FROM {$this->table}";
        return $this->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $sql = "SELECT id, username, email, created_at FROM {$this->table} WHERE id = :id";
        return $this->query($sql, ['id' => $id])->fetch(\PDO::FETCH_ASSOC);
    }

    public function createRequest($data)
    {
        if (empty($data['url']) || empty($data['name']) || empty($data['email'])) {
            throw new \InvalidArgumentException('All fields are required');
        }

        $sql = "INSERT INTO {$this->table} (url, name, email, time) 
                VALUES (:url, :name, :email, :time)";

        return $this->execute($sql, [
            'url' => $data['url'],
            'name' => $data['name'],
            'email' => $data['email'],
            'time' => $data['time'],
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

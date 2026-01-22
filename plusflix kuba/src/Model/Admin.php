<?php
namespace App\Model;
use App\Service\Config;
use PDO;
class Admin
{
    private ?int $id = null;
    private ?string $login = null;
    private ?string $password = null;
    public function getId(): ?int { return $this->id; }
    public function getLogin(): ?string { return $this->login; }
    public function getPassword(): ?string { return $this->password; }
    public static function findByLogin(string $login): ?self
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT * FROM admins WHERE login = :login';
        $statement = $pdo->prepare($sql);
        $statement->execute(['login' => $login]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        $admin = new self();
        $admin->id = $row['id'];
        $admin->login = $row['login'];
        $admin->password = $row['password'];
        return $admin;
    }
}
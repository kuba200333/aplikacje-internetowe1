<?php
namespace App\Model;
use App\Service\Config;
use PDO;
class Category
{
    private ?int $id = null;
    private ?string $name = null;
    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): void { $this->name = $name; }
    public static function fromArray(array $array): self
    {
        $category = new self();
        $category->id = $array['id'] ?? null;
        $category->name = $array['name'] ?? null;
        return $category;
    }
    public static function findAll(): array
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT * FROM categories';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $categories = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $categories[] = self::fromArray($row);
        }
        return $categories;
    }
    public static function find(int $id): ?self
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT * FROM categories WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ? self::fromArray($row) : null;
    }
    public function save(): void
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        if (!$this->id) {
            $sql = "INSERT INTO categories (name) VALUES (:name)";
            $statement = $pdo->prepare($sql);
            $statement->execute(['name' => $this->name]);
            $this->id = $pdo->lastInsertId();
        } else {
            $sql = "UPDATE categories SET name = :name WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute(['name' => $this->name, 'id' => $this->id]);
        }
    }
    public function delete(): void
    {
        if ($this->id) {
            $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
            $statement = $pdo->prepare("DELETE FROM categories WHERE id = :id");
            $statement->execute(['id' => $this->id]);
            $this->id = null;
        }
    }

    public static function validate(array $data): array
    {
        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Nazwa jest wymagana';
        }
        return $errors;
    }
}
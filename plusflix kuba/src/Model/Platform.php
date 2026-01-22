<?php
namespace App\Model;
use App\Service\Config;
use PDO;
class Platform
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $url = null;
    private ?string $logo_path = null;
    private ?string $details = null; 
    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): void { $this->name = $name; }
    public function getUrl(): ?string { return $this->url; }
    public function setUrl(?string $url): void { $this->url = $url; }
    public function getLogoPath(): ?string { return $this->logo_path; }
    public function setLogoPath(?string $logoPath): void { $this->logo_path = $logoPath; }
    public function getDetails(): ?string { return $this->details; }
    public function setDetails(?string $details): void { $this->details = $details; }
    public static function fromArray(array $array): self
    {
        $obj = new self();
        $obj->id = $array['id'] ?? null;
        $obj->name = $array['name'] ?? null;
        $obj->url = $array['url'] ?? null;
        $obj->logo_path = $array['logo_path'] ?? null;
        $obj->details = $array['details'] ?? null; 
        return $obj;
    }
    public static function findAll(): array
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $statement = $pdo->prepare('SELECT * FROM platforms');
        $statement->execute();
        $platforms = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $platforms[] = self::fromArray($row);
        }
        return $platforms;
    }
    public static function find(int $id): ?self
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $statement = $pdo->prepare('SELECT * FROM platforms WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ? self::fromArray($row) : null;
    }
    public static function findByMovieId(int $movieId): array
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT p.*, mp.details 
                FROM platforms p
                JOIN movie_platform mp ON p.id = mp.platform_id
                WHERE mp.movie_id = :movie_id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['movie_id' => $movieId]);
        $platforms = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $platforms[] = self::fromArray($row);
        }
        return $platforms;
    }
    public function save(): void 
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        if (!$this->id) {
            $sql = "INSERT INTO platforms (name, url, logo_path) VALUES (:name, :url, :logo_path)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->name,
                'url' => $this->url,
                'logo_path' => $this->logo_path
            ]);
            $this->id = $pdo->lastInsertId();
        } else {
            $sql = "UPDATE platforms SET name = :name, url = :url, logo_path = :logo_path WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->name,
                'url' => $this->url,
                'logo_path' => $this->logo_path,
                'id' => $this->id
            ]);
        }
    }

    public function delete(): void 
    {
        if ($this->id) {
            $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
            // Remove connections first (optional if CASCADE is set, but better safe)
            $pdo->prepare("DELETE FROM movie_platform WHERE platform_id = :id")->execute(['id' => $this->id]);
            $statement = $pdo->prepare("DELETE FROM platforms WHERE id = :id");
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
        if (empty($data['url'])) {
            $errors['url'] = 'URL jest wymagany';
        } elseif (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
            $errors['url'] = 'Podaj poprawny adres URL';
        }
        return $errors;
    }
}
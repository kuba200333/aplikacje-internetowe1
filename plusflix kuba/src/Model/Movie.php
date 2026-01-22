<?php
namespace App\Model;
use App\Service\Config;
use PDO;
class Movie
{
    private ?int $id = null;
    private ?string $title = null;
    private ?int $year = null;
    private ?int $duration = null;
    private ?string $description = null;
    private ?string $image_path = null;
    private ?int $cat_id = null;
    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(?string $title): void { $this->title = $title; }
    public function getYear(): ?int { return $this->year; }
    public function setYear(?int $year): void { $this->year = $year; }
    public function getDuration(): ?int { return $this->duration; }
    public function setDuration(?int $duration): void { $this->duration = $duration; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function getImagePath(): string 
    {
        if (empty($this->image_path)) {
            return 'images/bez_plakatu.png';
        }
        return $this->image_path;
    }
    public function setImagePath(?string $imagePath): void { $this->image_path = $imagePath; }
    public function getCatId(): ?int { return $this->cat_id; }
    public function setCatId(?int $catId): void { $this->cat_id = $catId; }
    public static function fromArray(array $array): self
    {
        $movie = new self();
        $movie->id = $array['id'] ?? null;
        $movie->title = $array['title'] ?? null;
        $movie->year = $array['year'] ?? null;
        $movie->duration = $array['duration'] ?? null;
        $movie->description = $array['description'] ?? null;
        $movie->image_path = $array['image_path'] ?? null;
        $movie->cat_id = $array['cat_id'] ?? null;
        return $movie;
    }
    public static function findAll(): array
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT * FROM movies';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $movies = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $movies[] = self::fromArray($row);
        }
        return $movies;
    }
    public static function find(int $id): ?self
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT * FROM movies WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ? self::fromArray($row) : null;
    }
    public function save(): void
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        if (!$this->id) {
            $sql = "INSERT INTO movies (title, year, duration, description, image_path, cat_id) VALUES (:title, :year, :duration, :description, :image_path, :cat_id)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'title' => $this->title,
                'year' => $this->year,
                'duration' => $this->duration,
                'description' => $this->description,
                'image_path' => $this->image_path,
                'cat_id' => $this->cat_id
            ]);
            $this->id = $pdo->lastInsertId();
        } else {
            $sql = "UPDATE movies SET title = :title, year = :year, duration = :duration, description = :description, image_path = :image_path, cat_id = :cat_id WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'title' => $this->title,
                'year' => $this->year,
                'duration' => $this->duration,
                'description' => $this->description,
                'image_path' => $this->image_path,
                'cat_id' => $this->cat_id,
                'id' => $this->id
            ]);
        }
    }
    public function delete(): void
    {
        if ($this->id) {
            $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
            $statement = $pdo->prepare("DELETE FROM movies WHERE id = :id");
            $statement->execute(['id' => $this->id]);
            $this->id = null;
        }
    }
    public function updatePlatforms(array $platformIds): void
    {
        if (!$this->id) return;
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        
        $pdo->prepare("DELETE FROM movie_platform WHERE movie_id = :id")->execute(['id' => $this->id]);
        
        if (!empty($platformIds)) {
            $stmt = $pdo->prepare("INSERT INTO movie_platform (movie_id, platform_id) VALUES (:movie_id, :platform_id)");
            foreach ($platformIds as $platformId) {
                $stmt->execute(['movie_id' => $this->id, 'platform_id' => $platformId]);
            }
        }
    }

    public static function findTopRated(int $limit = 10): array
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT m.*, AVG(r.rating) as avg_rating 
                FROM movies m
                LEFT JOIN ratings r ON m.id = r.movie_id
                GROUP BY m.id
                ORDER BY avg_rating DESC
                LIMIT :limit';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        $movies = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $movies[] = self::fromArray($row);
        }
        return $movies;
    }

    public static function validate(array $data): array
    {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors['title'] = 'Tytuł jest wymagany';
        }
        
        if (empty($data['year'])) {
            $errors['year'] = 'Rok jest wymagany';
        } elseif (!filter_var($data['year'], FILTER_VALIDATE_INT) || strlen((string)$data['year']) !== 4) {
            $errors['year'] = 'Rok musi być poprawnym rokiem (4 cyfry)';
        }
        
        if (empty($data['duration'])) {
            $errors['duration'] = 'Czas trwania jest wymagany';
        } elseif (!filter_var($data['duration'], FILTER_VALIDATE_INT)) {
            $errors['duration'] = 'Czas trwania musi być liczbą';
        }
        
        if (empty($data['description'])) {
            $errors['description'] = 'Opis jest wymagany';
        }

        return $errors;
    }
}
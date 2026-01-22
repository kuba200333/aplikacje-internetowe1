<?php
namespace App\Model;
use App\Service\Config;
use PDO;
class Rating
{
    private ?int $id = null;
    private ?int $movie_id = null;
    private ?int $rating = null;
    private ?string $cookie_id = null;
    public function getId(): ?int { return $this->id; }
    public function getMovieId(): ?int { return $this->movie_id; }
    public function setMovieId(?int $movieId): void { $this->movie_id = $movieId; }
    public function getRating(): ?int { return $this->rating; }
    public function setRating(?int $rating): void { $this->rating = $rating; }
    public function getCookieId(): ?string { return $this->cookie_id; }
    public function setCookieId(?string $cookieId): void { $this->cookie_id = $cookieId; }
    public static function fromArray(array $array): self
    {
        $obj = new self();
        $obj->id = $array['id'] ?? null;
        $obj->movie_id = $array['movie_id'] ?? null;
        $obj->rating = $array['rating'] ?? null;
        $obj->cookie_id = $array['cookie_id'] ?? null;
        return $obj;
    }
    public static function findByUserAndMovie(string $cookieId, int $movieId): ?self
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT * FROM ratings WHERE cookie_id = :cookie_id AND movie_id = :movie_id';
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'cookie_id' => $cookieId, 
            'movie_id' => $movieId
        ]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ? self::fromArray($row) : null;
    }
    public static function getAvgRating(int $movieId): float
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT AVG(rating) as avg_rating FROM ratings WHERE movie_id = :movie_id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['movie_id' => $movieId]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['avg_rating'] ? round((float)$result['avg_rating'], 1) : 0.0;
    }

    public static function getGlobalAvgRating(): float
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT AVG(rating) as avg_rating FROM ratings';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['avg_rating'] ? round((float)$result['avg_rating'], 1) : 0.0;
    }
    public function save(): void
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        if (!$this->id) {
            $sql = "INSERT INTO ratings (movie_id, rating, cookie_id) VALUES (:movie_id, :rating, :cookie_id)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'movie_id' => $this->movie_id,
                'rating' => $this->rating,
                'cookie_id' => $this->cookie_id
            ]);
            $this->id = $pdo->lastInsertId();
        } else {
            $sql = "UPDATE ratings SET rating = :rating WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'rating' => $this->rating,
                'id' => $this->id
            ]);
        }
    }
    public static function find(int $id): ?self
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $statement = $pdo->prepare('SELECT * FROM ratings WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ? self::fromArray($row) : null;
    }
    public function delete(): void
    {
        if ($this->id) {
            $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
            $statement = $pdo->prepare("DELETE FROM ratings WHERE id = :id");
            $statement->execute(['id' => $this->id]);
            $this->id = null;
        }
    }
}
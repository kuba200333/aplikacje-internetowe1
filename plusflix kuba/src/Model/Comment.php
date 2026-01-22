<?php
namespace App\Model;
use App\Service\Config;
use PDO;
class Comment
{
    private ?int $id = null;
    private ?int $movie_id = null;
    private ?string $nick = null;
    private ?string $content = null;
    private ?string $created_at = null;
    private ?string $cookie_id = null;
    private ?int $userRating = null; 
    private ?string $movieTitle = null;
    private ?string $movieImage = null;

    public function getId(): ?int { return $this->id; }
    public function getMovieId(): ?int { return $this->movie_id; }
    public function setMovieId(?int $movieId): void { $this->movie_id = $movieId; }
    public function getNick(): ?string { return $this->nick; }
    public function setNick(?string $nick): void { $this->nick = $nick; }
    public function getContent(): ?string { return $this->content; }
    public function setContent(?string $content): void { $this->content = $content; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function setCreatedAt(?string $date): void { $this->created_at = $date; }
    public function getCookieId(): ?string { return $this->cookie_id; }
    public function setCookieId(?string $cookieId): void { $this->cookie_id = $cookieId; }
    public function getUserRating(): int { return $this->userRating ?? 0; }
    public function getMovieTitle(): ?string { return $this->movieTitle; }
    public function getMovieImage(): string 
    {
        return empty($this->movieImage) ? 'images/bez_plakatu.png' : $this->movieImage;
    }

    public static function fromArray(array $array): self
    {
        $obj = new self();
        $obj->id = $array['id'] ?? null;
        $obj->movie_id = $array['movie_id'] ?? null;
        $obj->nick = $array['nick'] ?? null;
        $obj->content = $array['content'] ?? null;
        $obj->created_at = $array['created_at'] ?? null;
        $obj->cookie_id = $array['cookie_id'] ?? null;
        $obj->userRating = $array['rating_val'] ?? 0;
        $obj->movieTitle = $array['movie_title'] ?? null;
        $obj->movieImage = $array['movie_image'] ?? null;
        return $obj;
    }

    public static function findByMovieId(int $movieId): array
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT c.*, r.rating as rating_val, m.title as movie_title, m.image_path as movie_image
                FROM comments c 
                LEFT JOIN ratings r ON c.cookie_id = r.cookie_id AND c.movie_id = r.movie_id
                LEFT JOIN movies m ON c.movie_id = m.id
                WHERE c.movie_id = :movie_id 
                ORDER BY c.created_at DESC';
        $statement = $pdo->prepare($sql);
        $statement->execute(['movie_id' => $movieId]);
        $comments = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = self::fromArray($row);
        }
        return $comments;
    }

    public static function findAll(): array
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $sql = 'SELECT c.*, r.rating as rating_val, m.title as movie_title, m.image_path as movie_image
                FROM comments c 
                LEFT JOIN ratings r ON c.cookie_id = r.cookie_id AND c.movie_id = r.movie_id
                LEFT JOIN movies m ON c.movie_id = m.id
                ORDER BY c.created_at DESC';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $comments = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = self::fromArray($row);
        }
        return $comments;
    }
    public static function find(int $id): ?self
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        $statement = $pdo->prepare('SELECT * FROM comments WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ? self::fromArray($row) : null;
    }
    public function save(): void
    {
        $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
        if (!$this->id) {
            $sql = "INSERT INTO comments (movie_id, nick, content, cookie_id) VALUES (:movie_id, :nick, :content, :cookie_id)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'movie_id' => $this->movie_id,
                'nick' => $this->nick,
                'content' => $this->content,
                'cookie_id' => $this->cookie_id
            ]);
            $this->id = $pdo->lastInsertId();
        } else {
            $sql = "UPDATE comments SET nick = :nick, content = :content WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'nick' => $this->nick,
                'content' => $this->content,
                'id' => $this->id
            ]);
        }
    }
    public function delete(): void
    {
        if ($this->id) {
            $pdo = new PDO((new Config())->get('db_dsn'), (new Config())->get('db_user'), (new Config())->get('db_pass'));
            $statement = $pdo->prepare("DELETE FROM comments WHERE id = :id");
            $statement->execute(['id' => $this->id]);
            $this->id = null;
        }
    }
}
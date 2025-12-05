<?php
namespace App\Model;

use App\Service\Config;

class Album
{
    private ?int $id = null;
    private ?string $title = null;
    private ?string $artist = null;
    private ?int $releaseYear = null;
    private ?string $genre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Album
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Album
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): Album
    {
        $this->artist = $artist;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(?int $releaseYear): Album
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): Album
    {
        $this->genre = $genre;

        return $this;
    }

    public static function fromArray(array $data): Album
    {
        $album = new self();
        if (isset($data['id'])) {
            $album->id = $data['id'];
        }
        $album->title = $data['title'] ?? null;
        $album->artist = $data['artist'] ?? null;
        $album->releaseYear = $data['release_year'] ?? null;
        $album->genre = $data['genre'] ?? null;

        return $album;
    }


    public function fill($array): Album
    {
        if (isset($array['id']) && !$this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['artist'])) {
            $this->setArtist($array['artist']);
        }
        if (isset($array['release_year'])) {
            $this->setReleaseYear($array['release_year']);
        }
        if (isset($array['genre'])) {
            $this->setGenre($array['genre']);
        }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM album';
        $stmt = $pdo->query($sql);
        $albumsArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($albumData) {
            return self::fromArray($albumData);
        }, $albumsArray);
    }

    public static function find($id): ?Album
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM album WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $albumArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$albumArray) {
            return null;
        }
        $album = Album::fromArray($albumArray);

        return $album;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));

        if ($this->id === null) {
            $stmt = $pdo->prepare(
                'INSERT INTO album (title, artist, release_year, genre) VALUES (:title, :artist, :releaseYear, :genre)'
            );
            $stmt->execute([
                ':title' => $this->title,
                ':artist' => $this->artist,
                ':releaseYear' => $this->releaseYear,
                ':genre' => $this->genre,
            ]);
            $this->id = (int)$pdo->lastInsertId();
        } else {
            $stmt = $pdo->prepare(
                'UPDATE album SET title = :title, artist = :artist, release_year = :releaseYear, genre = :genre WHERE id = :id'
            );
            $stmt->execute([
                ':title' => $this->title,
                ':artist' => $this->artist,
                ':releaseYear' => $this->releaseYear,
                ':genre' => $this->genre,
                ':id' => $this->id,
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM album WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $this->getId(),
        ]);

        $this->setId(null);
        $this->setTitle(null);
        $this->setArtist(null);
        $this->setReleaseYear(null);
        $this->setGenre(null);
    }
}

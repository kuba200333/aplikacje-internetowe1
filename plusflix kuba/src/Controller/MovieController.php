<?php
namespace App\Controller;
use App\Exception\NotFoundException;
use App\Model\Movie;
use App\Model\Category; 
use App\Model\Platform; 
use App\Model\Comment;
use App\Service\Router;
use App\Service\Templating;
class MovieController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $q = null;
        $catId = null;
        $platformId = null;
        $sort = null;
        if (!$q && !$catId && !$platformId && !$sort) {
            $movies = Movie::findTopRated(10); 
            $categories = Category::findAll();
            $platforms = Platform::findAll();
            return $templating->render('movie/index.html.php', [
                'movies' => $movies,
                'categories' => $categories,
                'platforms' => $platforms,
                'router' => $router,
            ]);
        }
        $allMovies = Movie::findAll();
        $filteredMovies = [];
        foreach ($allMovies as $movie) {
            if ($q && stripos($movie->getTitle(), $q) === false) {
                continue;
            }
            if ($catId && $movie->getCatId() != $catId) {
                continue;
            }
            if ($platformId) {
                $moviePlatforms = Platform::findByMovieId($movie->getId());
                $isOnPlatform = false;
                foreach ($moviePlatforms as $mp) {
                    if ($mp->getId() == $platformId) {
                        $isOnPlatform = true;
                        break;
                    }
                }
                if (!$isOnPlatform) continue;
            }
            $filteredMovies[] = $movie;
        }
        if ($sort === 'best') {
            usort($filteredMovies, function($a, $b) {
                $rateA = \App\Model\Rating::getAvgRating($a->getId());
                $rateB = \App\Model\Rating::getAvgRating($b->getId());
                return $rateB <=> $rateA; 
            });
        } elseif ($sort === 'newest') {
            usort($filteredMovies, function($a, $b) {
                return $b->getYear() <=> $a->getYear();
            });
        }
        $count = count($filteredMovies);
        $totalAvg = 0;
        if ($count > 0) {
            $sum = 0;
            foreach ($filteredMovies as $m) {
                $sum += \App\Model\Rating::getAvgRating($m->getId());
            }
            $totalAvg = round($sum / $count, 2);
        }
        $results = [];
        foreach ($filteredMovies as $movie) {
            $results[] = [
                'movie' => $movie,
                'platforms' => Platform::findByMovieId($movie->getId()),
                'avg_rating' => \App\Model\Rating::getAvgRating($movie->getId())
            ];
        }
        return $templating->render('movie/search.html.php', [
            'results' => $results,
            'count' => $count,
            'totalAvg' => $totalAvg,
            'router' => $router,
            'currentParams' => $_GET 
        ]);
    }
    public function showAction(Templating $templating, Router $router): ?string
    {
        $id = $_REQUEST['id'] ?? null;
        $movie = Movie::find($id);
        if (! $movie) {
            throw new NotFoundException("Brak filmu o id $id");
        }
        $comments = Comment::findByMovieId($id);
        $platforms = Platform::findByMovieId((int)$id);
        $avgRating = \App\Model\Rating::getAvgRating($id);
        $favorites = [];
        $isFavorite = in_array($movie->getId(), $favorites);
        $html = $templating->render('movie/show.html.php', [
            'movie' => $movie,
            'comments' => $comments,
            'platforms' => $platforms,
            'avgRating' => $avgRating,
            'isFavorite' => $isFavorite,
            'router' => $router,
        ]);
        return $html;
    }
    public function adminIndexAction(Templating $templating, Router $router): ?string
    {
        $movies = Movie::findAll();
        $categories = Category::findAll();
        $platforms = Platform::findAll();
        $comments = Comment::findAll();

        $movieData = [];
        foreach ($movies as $movie) {
            $movieData[] = [
                'movie' => $movie,
                'platforms' => Platform::findByMovieId($movie->getId()),
                'avg_rating' => \App\Model\Rating::getAvgRating($movie->getId())
            ];
        }

        $globalAvgRating = \App\Model\Rating::getGlobalAvgRating();

        $html = $templating->render('movie/admin_index.html.php', [
            'movies' => $movieData,
            'globalAvgRating' => $globalAvgRating,
            'router' => $router,
            'moviesCount' => 0,
            'categoriesCount' => 0,
            'platformsCount' => 0,
            'commentsCount' => 0
        ]);
        return $html;
    }
    public function createAction(?array $requestPost, Templating $templating, Router $router): ?string
    {
        $movie = new Movie();
        $errors = [];
        $data = [];

        if ($requestPost) {
            $data = $requestPost['movie'] ?? [];
            
            $uploadedPath = $this->handleFileUpload($_FILES['image_file'] ?? null);
            if ($uploadedPath) {
                $data['image_path'] = $uploadedPath;
            }

            // Populate movie object immediately for potential re-render
            $movie->setTitle($data['title'] ?? '');
            $movie->setYear(isset($data['year']) ? (int)$data['year'] : null);
            $movie->setDuration(isset($data['duration']) ? (int)$data['duration'] : null);
            $movie->setDescription($data['description'] ?? '');
            $movie->setImagePath($data['image_path'] ?? null);
            $movie->setCatId(isset($data['cat_id']) ? (int)$data['cat_id'] : null);

            $errors = Movie::validate($data);

            if (empty($errors)) {
                $movie->save();
                
                if (isset($data['platforms']) && is_array($data['platforms'])) {
                    $movie->updatePlatforms($data['platforms']);
                }

                $path = $router->generatePath('admin-movie-index');
                $router->redirect($path);
                return null;
            }
        }

        $categories = Category::findAll();
        $platforms = Platform::findAll();
        $html = $templating->render('movie/create.html.php', [
            'movie' => $movie,
            'categories' => $categories,
            'platforms' => $platforms,
            'router' => $router,
            'errors' => $errors,
        ]);
        return $html;
    }
    public function editAction(Templating $templating, Router $router): ?string
    {
        $id = $_REQUEST['id'] ?? null;
        $movie = Movie::find($id);
        if (! $movie) {
            throw new NotFoundException("Missing movie with id $id");
        }
        
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST['movie'] ?? [];
            
            $uploadedPath = $this->handleFileUpload($_FILES['image_file'] ?? null);
            if ($uploadedPath) {
                $data['image_path'] = $uploadedPath;
            } else {
                // Keep existing image if not replaced
                 $data['image_path'] = $movie->getImagePath();
            }

            // Update local object for potential re-render
            $movie->setTitle($data['title']);
            $movie->setYear((int)$data['year']);
            $movie->setDuration((int)$data['duration']);
            $movie->setDescription($data['description']);
            $movie->setImagePath($data['image_path']);
            $movie->setCatId((int)$data['cat_id']);

            $errors = Movie::validate($data);

            if (empty($errors)) {
                $movie->save();

                if (isset($data['platforms']) && is_array($data['platforms'])) {
                    $movie->updatePlatforms($data['platforms']);
                }

                $path = $router->generatePath('admin-movie-index');
                $router->redirect($path);
                return null;
            }
        }
        $categories = Category::findAll();
        $platforms = Platform::findAll();
        $moviePlatforms = Platform::findByMovieId($movie->getId());
        
        $html = $templating->render('movie/edit.html.php', [
            'movie' => $movie,
            'categories' => $categories,
            'platforms' => $platforms,
            'moviePlatforms' => $moviePlatforms,
            'router' => $router,
            'errors' => $errors,
        ]);
        return $html;
    }
    public function deleteAction(Router $router): ?string
    {
        $id = $_POST['id'] ?? null;
        $movie = Movie::find($id);
        if ($movie) {
            $movie->delete();
        }
        $path = $router->generatePath('admin-movie-index');
        $router->redirect($path);
        return null;
    }
    private function handleFileUpload(?array $file): ?string
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        $uploadDir = 'assets/uploads/';
        // Ensure directory existence check if not done elsewhere, but we assume it exists.
        
        $info = pathinfo($file['name']);
        $ext = $info['extension'] ?? 'jpg';
        $filename = uniqid('movie_') . '.' . $ext;
        $target = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $target;
        }

        return null;
    }
}
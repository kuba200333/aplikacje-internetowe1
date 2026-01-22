<?php
namespace App\Controller;
use App\Model\Movie;
use App\Model\Platform;
use App\Model\Rating;
use App\Service\Router;
use App\Service\Templating;
class FavoriteController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $favoritesIds = [];
        $sort = $_GET['sort'] ?? null; 
        $movies = [];
        if (!empty($favoritesIds)) {
            foreach ($favoritesIds as $id) {
                $movie = Movie::find($id);
                if ($movie) {
                    $movies[] = $movie;
                }
            }
        }
        if ($sort === 'best') {
            usort($movies, function($a, $b) {
                $rateA = Rating::getAvgRating($a->getId());
                $rateB = Rating::getAvgRating($b->getId());
                return $rateB <=> $rateA; 
            });
        } elseif ($sort === 'newest') {
            usort($movies, function($a, $b) {
                return $b->getYear() <=> $a->getYear(); 
            });
        }
        $count = count($movies);
        $totalAvg = 0;
        if ($count > 0) {
            $sum = 0;
            foreach ($movies as $m) {
                $sum += Rating::getAvgRating($m->getId());
            }
            $totalAvg = round($sum / $count, 2);
        }
        $results = [];
        foreach ($movies as $movie) {
            $results[] = [
                'movie' => $movie,
                'platforms' => Platform::findByMovieId($movie->getId()),
                'avg_rating' => Rating::getAvgRating($movie->getId())
            ];
        }
        return $templating->render('favorite/index.html.php', [
            'results' => $results,
            'count' => $count,
            'totalAvg' => $totalAvg,
            'router' => $router,
            'currentParams' => $_GET 
        ]);
    }
    public function toggleAction(Router $router): ?string
    {
        $movieId = (int) ($_GET['id'] ?? 0);
        if ($movieId) {

        }
        $referer = $_SERVER['HTTP_REFERER'] ?? $router->generatePath('movie-index');
        header("Location: $referer");
        return null;
    }
}
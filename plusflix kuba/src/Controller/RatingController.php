<?php
namespace App\Controller;
use App\Model\Rating;
use App\Service\Router;
class RatingController
{
    public function addAction(array $requestPost, Router $router): ?string
    {
        $movieId = $requestPost['movie_id'] ?? null;
        $ratingValue = $requestPost['rating'] ?? null;
        if ($movieId && $ratingValue) {
            $cookieId= 0; 
            $rating = new Rating();
            $rating->setMovieId($movieId);
            $rating->setRating((int)$ratingValue);
            $rating->setCookieId($cookieId);
            $rating->save();
            $router->redirect($router->generatePath('movie-show', ['id' => $movieId]));
        } else {
            $router->redirect($router->generatePath('movie-index'));
        }
        return null;
    }
    public function deleteAction(Router $router): ?string
    {
        $id = $_POST['id'] ?? null;
        $rating = Rating::find($id);
        if ($rating) {
            $rating->delete();
        }
        $router->redirect($router->generatePath('admin-dashboard'));
        return null;
    }
}
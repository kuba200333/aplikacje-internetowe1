<?php
namespace App\Controller;
use App\Model\Comment;
use App\Model\Rating;
use App\Service\Router;
class CommentController
{
    public function addAction(array $requestPost, Router $router): ?string
    {
        $movieId = $requestPost['movie_id'] ?? null;
        $ratingValue = $requestPost['rating'] ?? null; 
        $nick = $requestPost['nick'] ?? 'Anonim';
        $content = $requestPost['content'] ?? '';
        if ($movieId) {
            if (!isset($_COOKIE['plusflix_uid'])) {
                $userId = uniqid('usr_', true);
                setcookie('plusflix_uid', $userId, time() + (86400 * 365), "/");
            } else {
                $userId = $_COOKIE['plusflix_uid'];
            }
            $existingRating = Rating::findByUserAndMovie($userId, (int)$movieId);
            if ($existingRating) {
                $router->redirect($router->generatePath('movie-show', ['id' => $movieId]));
                return null;
            }
            if ($ratingValue) {
                $rating = new Rating();
                $rating->setMovieId((int)$movieId);
                $rating->setRating((int)$ratingValue);
                $rating->setCookieId($userId);
                $rating->save();
            }
            $comment = new Comment();
            $comment->setMovieId((int)$movieId);
            $comment->setNick($nick);
            $comment->setContent($content);
            $comment->setCookieId($userId); 
            $comment->save();
            $router->redirect($router->generatePath('movie-show', ['id' => $movieId]));
        } else {
            $router->redirect($router->generatePath('movie-index'));
        }
        return null;
    }
    public function deleteAction(Router $router): ?string
    {
        $id = $_POST['id'] ?? null;
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();
        }
        $router->redirect($router->generatePath('admin-dashboard'));
        return null;
    }
}
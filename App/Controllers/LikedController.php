<?php

namespace App\Controllers;

use \App\Helper\Session;
use App\Models\Like;
use App\Models\Post;
use \Core\View;
use \Core\Controller;

/**
 * Liked controller
 */
class LikedController extends Controller
{
    public function __construct()
    {
        $session = Session::getInstance();
        if (!$session->isSignedIn()) {
            header('Location: /login');
        }
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function index()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $liked = Like::where('user_id', $_SESSION['userId'])->orderBy('created_at', 'desc')->get();
        $likedPosts = [];
        foreach ($liked as $like) {
            $likedPosts[] = Post::find($like->post_id);
        }

        $session->stopLoadingScreen();

        View::renderTemplate('Liked/index.html', ['session' => $_SESSION, 'likedPosts' => $likedPosts]);
    }
}

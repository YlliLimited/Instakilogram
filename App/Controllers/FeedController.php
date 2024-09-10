<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\Post;
use \Core\View;
use \Core\Controller;

/**
 * Feed controller
 */
class FeedController extends Controller
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

        $posts = Post::orderBy('created_at', 'desc')->limit(50)->get();
        
        $session->stopLoadingScreen();

        View::renderTemplate('Feed/index.html', ['session' => $_SESSION, 'posts' => $posts]);
    }
}

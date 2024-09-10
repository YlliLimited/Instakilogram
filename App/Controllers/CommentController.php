<?php

namespace App\Controllers;

use \App\Helper\Session;
use App\Models\Comment;
use \Core\View;
use \Core\Controller;

/**
 * Comment controller
 */
class CommentController extends Controller
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
        $comments = Comment::where('post_id', $_GET['id'])->orderBy('created_at', 'desc')->get();
        $session->stopLoadingScreen();
        View::renderTemplate('Comments/index.html', ['session' => $_SESSION, 'comments' => $comments]);
    }

    public function edit()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();
        $comment = Comment::find($_GET['id']);
        $session->stopLoadingScreen();

        View::renderTemplate('Comments/edit.html', ['session' => $_SESSION, 'comment' => $comment]);
    }

    public function update()
    {

        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();
        $comment = Comment::find($_POST['id']);
        $comment->content = $_POST['content'];
        $comment->update();
        $session->stopLoadingScreen();


        header('Location: /comments/view?id=' . $comment->post_id);
    }

    public function store()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();
        Comment::create($_POST);
        $session->stopLoadingScreen();
    }

    public function destroy()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();
        Comment::find($_POST['id'])->delete();
        $session->stopLoadingScreen();
    }
}

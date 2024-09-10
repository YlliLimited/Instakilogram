<?php

namespace App\Controllers;

use \App\Helper\Session;
use App\Helper\UploadPhoto;
use \App\Models\Like;
use \App\Models\Post;
use \App\Controllers\NotificationController;
use \Core\View;
use \Core\Controller;

/**
 * Post controller
 */
class PostController extends Controller
{

    use UploadPhoto;

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

    public function show()
    {
        View::renderTemplate('Post/view.html', ['session' => $_SESSION, 'post' => Post::find($_GET['id'])]);
    }

    public function create()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $session = Session::getInstance();
        $message = '';

        if (!empty($session->message)) {
            $message = $session->message;
        }

        $session->stopLoadingScreen();

        View::renderTemplate('Post/create.html', ['session' => $_SESSION, 'message' => $message]);
    }

    public function store()
    {

        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        if (empty($_FILES['photo']) || empty($_POST['caption'])) {
            header("Location: /post/create");
            exit;
        }

        $this->src = "../public/Uploads/PostPhotos/";
        $this->activeSrc = "/Uploads/PostPhotos/";

        $this->startupLoad($_FILES['photo']);
        $uploadedFile = $this->uploadFile();

        if ($uploadedFile) {
            $_POST['photo'] = $this->uploadActiveFile;
        } else {
            $errorMessage = '';

            foreach ($this->errors as $error) {
                $errorMessage .= "{$error}. ";
            }
            ;

            $session->setMessage($errorMessage);

            $session->stopLoadingScreen();

            header("Location: /post/create");
            exit;
        }

        
        Post::create($_POST);
        NotificationController::sendNotification($_SESSION['userId'], "Your post has been published.");
        $session->stopLoadingScreen();

        header("Location: /profile");
        exit;
    }

    public function edit()
    {
        View::renderTemplate('Post/edit.html', ['session' => $_SESSION, 'post' => Post::find($_GET['id'])]);
    }

    public function update()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $post = Post::find($_POST['id']);
        $post->caption = $_POST['caption'];
        $post->update();

        NotificationController::sendNotification($_SESSION['userId'], "Your post has been updated.");

        $session->stopLoadingScreen();

        header("Location: /profile");
    }

    public function destroy()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $post = Post::find($_POST['id']);

        Like::where('post_id', $_POST['id'])->delete();
        unlink("../public/" . $post->photo);
        Post::find($_POST['id'])->delete();
        
        NotificationController::sendNotification($_SESSION['userId'], "Your post has been removed.");

        $session->stopLoadingScreen();

        header('Location: /profile');
    }

    public function like()
    {
        Like::create($_POST);
    }

    public function unlike()
    {
        
        Like::where('user_id', $_POST['user_id'])->where('post_id', $_POST['post_id'])->delete();
    }
}

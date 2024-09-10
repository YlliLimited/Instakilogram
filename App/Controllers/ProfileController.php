<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\Follow;
use App\Models\User;
use \Core\View;
use \Core\Controller;

/**
 * Home controller
 */
class ProfileController extends Controller
{
    public function __construct()
    {
        $session = Session::getInstance();
        if (!$session->isSignedIn()){
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
        
        $isMe = null;
        $user = null;
        $amFollowing = null;

        $numFollowers = null;
        $numFollowing = null;
        

        if (isset($_GET['id']) && $_GET['id'] != $_SESSION['userId']) {
            $isMe = false;

            $numFollowers = Follow::where("following_id", $_GET['id'])->count();
            $numFollowing = Follow::where("follower_id", $_GET['id'])->count();


            if (Follow::where('follower_id', $_SESSION['userId'])->where('following_id', $_GET['id'])->first()) {
                $amFollowing = true;
            }

            if ($_GET['id'] != $_SESSION['userId']) {
                $user = User::find($_GET['id']);
            }
        } else {

            $isMe = true;
            $user = User::find($_SESSION['userId']);
            $amFollowing = false;

            $numFollowers = Follow::where("following_id", $_SESSION['userId'])->count();
            $numFollowing = Follow::where("follower_id", $_SESSION['userId'])->count();

        }

        $session->stopLoadingScreen();

        View::renderTemplate('Profile/index.html', ['session' => $_SESSION, 'user' => $user, 'amFollowing' => $amFollowing, 'numFollowers' => $numFollowers, 'numFollowing' => $numFollowing, 'isMe' => $isMe]);
    }
}

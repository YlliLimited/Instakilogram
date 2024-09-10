<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\Follow;
use App\Models\User;
use \Core\View;
use \Core\Controller;
use App\Controllers\NotificationController;

/**
 * Follow controller
 */
class FollowController extends Controller
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
    public function followers()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $isNotMe = isset($_GET['id']);
        $id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['userId'];

        $followersId = Follow::where('following_id', $id)->get();
        $followers = [];
        foreach ($followersId as $followerId) {
            $followers[] = User::find($followerId->follower_id);
        }

        $session->stopLoadingScreen();

        View::renderTemplate('Follow/followers.html', ['session' => $_SESSION, 'followers' => $followers, 'isNotMe' => $isNotMe]);
    }

    public function following()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $isNotMe = isset($_GET['id']);
        $id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['userId'];

        $followingsId = Follow::where('follower_id', $id)->get();
        $followings = [];
        foreach ($followingsId as $followingId) {
            $followings[] = User::find($followingId->following_id);
        }

        $session->stopLoadingScreen();

        View::renderTemplate('Follow/following.html', ['session' => $_SESSION, 'followings' => $followings, 'isNotMe' => $isNotMe]);
    }

    public function follow()
    {

        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $followerId = $_SESSION['userId'];
        $followingId = $_GET['id'];

        $follow = new Follow();
        $follow->follower_id = $followerId;
        $follow->following_id = $followingId;
        $follow->save();

        NotificationController::sendNotification($followingId, "{$_SESSION['user']['username']} started following you");

        $session->stopLoadingScreen();

        header("Location: /profile?id={$followingId}");

    }


    public function removeFollower()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();
        $followerId = $_POST['id'];
        $followingId = $_SESSION['userId'];

        Follow::where('follower_id', $followerId)->where('following_id', $followingId)->delete();
        $session->stopLoadingScreen();
    }


    public function removeFollowing()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $followerId = $_SESSION['userId'];
        $followingId = $_POST['id'];

        NotificationController::sendNotification($followingId, "{$_SESSION['user']['username']} stopped following you");

        Follow::where('follower_id', $followerId)->where('following_id', $followingId)->delete();

        $session->stopLoadingScreen();
    }
}

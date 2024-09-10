<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\Follow;
use App\Models\Setting;
use App\Models\User;
use App\Controllers\NotificationController;
use \Core\View;
use \Core\Controller;

/**
 * Settings controller
 */
class SettingsController extends Controller
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
        $message = '';

        if(!empty($session->message)) { 
            $message = $session->message;
        }

        $session->stopLoadingScreen();

        View::renderTemplate('Settings/index.html', ['session' => $_SESSION, 'message' => $message]);
    }

    public function destroyUser() {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        unlink("../public/" . $_SESSION['user']['profile_picture']);
        User::with('posts', 'comments', 'notifications', 'likes')->find($session->userId)->delete();
        Follow::where('follower_id', $session->userId)->orWhere('following_id', $session->userId)->delete();
        
        User::destroy($session->userId);
        $session->logout();

        $session->stopLoadingScreen();
        header('Location: /login');
    }

    public function updateUser() {

        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $_POST['bio'] = trim($_POST['bio']);

        $session = Session::getInstance();

        $userExists = User::where('username', $username)->orWhere('email', $email)->first();
        
        if($userExists && $userExists->id != $session->userId) {
            $session->setMessage("User already exists");
            $session->stopLoadingScreen();
            header("Location: /settings");
            exit;
        } else {
            User::find($session->userId)->update($_POST);
            $session->login(User::where('email', $email)->where('password', $password)->first());
            
            NotificationController::sendNotification($_SESSION['userId'], "Your profile has been updated successfully.");
            
            $session->stopLoadingScreen();
            header("Location: /profile");

            exit;
        }
    }


    public function editTheme() {
        $setting = new Setting();
        $setting = $setting::where('user_id', $_SESSION['userId'])->first();
        $setting->prefered_theme = $_POST['theme'];
        $setting->update();
        $_SESSION['user']['settings']['prefered_theme'] = $_POST['theme'];
        header("Location: /settings");
    }

}

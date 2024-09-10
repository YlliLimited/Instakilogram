<?php

namespace App\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Helper\Session;
use \Core\View;
use \Core\Controller;
use App\Controllers\NotificationController;

/**
 * Home controller
 */
class AuthController extends Controller
{
    use \App\Helper\UploadPhoto;


    /**
     * Show the index page
     *
     * @return void
     */
    public function loginPage()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();
        $message = '';

        if (!empty($session->message)) {
            $message = $session->message;
        }

        if (isset($session->userId)) {
            header("Location: /feed");
        }

        $session->stopLoadingScreen();
        View::renderTemplate('Auth/login.html', ['message' => $message]);
    }

    public function signupPage()
    {

        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();
        $message = '';

        if (!empty($session->message)) {
            $message = $session->message;
        }

        if (isset($session->userId)) {
            $session->stopLoadingScreen();
            header("Location: /feed");
        }
        
        $session->stopLoadingScreen();
        View::renderTemplate('Auth/signup.html', ['message' => $message]);
    }

    public function signupUser()
    {

        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
            $session->stopLoadingScreen();
            header("Location: /signup");
            exit;
        }


        if ($_FILES['profile_picture']['name']) {

            $this->src = "../public/Uploads/ProfilePictures/";
            $this->activeSrc = "/Uploads/ProfilePictures/";

            $this->startupLoad($_FILES['profile_picture']);
            $uploadedFile = $this->uploadFile();

            if ($uploadedFile) {
                $_POST['profile_picture'] = $this->uploadActiveFile;
            } else {
                $errorMessage = '';

                foreach ($this->errors as $error) {
                    $errorMessage .= "{$error}. ";
                }

                $session->setMessage($errorMessage);

                $session->stopLoadingScreen();

                header("Location: /signup");
                exit;
            }

        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userExists = User::where('username', $username)->orWhere('email', $email)->first();

        if ($userExists) {
            $session->setMessage("User already exists");
            $session->stopLoadingScreen();
            header("Location: /signup");
            exit;
        } else {
            User::create($_POST);
            $session->login(User::where('email', $email)->where('password', $password)->first());

            Setting::create(['user_id' => $session->userId]);

            NotificationController::sendNotification($session->userId, "Welcome to Instakilogram! The only real social network.");

            $session->stopLoadingScreen();
            header("Location: /feed");
            exit;
        }
    }

    public function loginUser()
    {

        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = User::where('email', $email)->where('password', $password)->first();

        if (!$user) {
            $session->setMessage("User does not exist");
            $session->stopLoadingScreen();
            header("Location: /login");
            exit;
        } else {
            $session->login($user);
            $session->stopLoadingScreen();
            header("Location: /feed");
        }
    }

    public function signoutUser()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();
        $session->logout();
        $session->stopLoadingScreen();
        header("Location: /login");
    }

}

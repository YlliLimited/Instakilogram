<?php

namespace App\Controllers;

use \App\Helper\Session;
use \App\Models\Notification;
use \Core\View;
use \Core\Controller;

/**
 * Notifications controller
 */
class NotificationController extends Controller
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

        $notifications = Notification::where('user_id', $session->userId)->orderBy('created_at', 'desc')->get();
        $count = $notifications->count();

        $session->stopLoadingScreen();

        View::renderTemplate('Notifications/index.html', ['session' => $_SESSION, 'notifications' => $notifications, 'count' => $count]);
    }

    public static function sendNotification($userId ,$content)
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $notification = new Notification();
        $notification->user_id = $userId;
        $notification->content = $content;
        $notification->save();

        $session->stopLoadingScreen();
    }

    public function destroy()
    {
        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $id = $_POST['id'];
        Notification::destroy($id);
        echo json_encode(['message' => "Successfully deleted notification"]);

        $session->stopLoadingScreen();
    }

    public function destroyAll()
    {

        $session = Session::getInstance();
        $session->setLoadingScreen();
        $session->loadingScreen();

        $session = Session::getInstance();
        Notification::where('user_id', $session->userId)->delete();

        $session->stopLoadingScreen();

        header('Location: /notifications');
    }
}

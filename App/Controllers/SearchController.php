<?php

namespace App\Controllers;

use App\Helper\Session;
use \App\Models\User;
use \Core\View;
use \Core\Controller;

/**
 * Search controller
 */
class SearchController extends Controller
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
        View::renderTemplate('Search/index.html', ['session' => $_SESSION]);
    }

    public function getProfiles() {
        
        if (isset($_POST)) {

            $username = $_POST['username'];
            $profiles = User::where('username','LIKE', "{$username}%")->where('id', '!=', $_SESSION['userId'])->limit(10)->get();
            echo json_encode($profiles);

        }
    }
}

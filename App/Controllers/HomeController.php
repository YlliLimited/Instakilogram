<?php

namespace App\Controllers;

use \App\Helper\Session;
use \Core\View;
use \Core\Controller;

/**
 * Home controller
 */
class HomeController extends Controller
{
    public function __construct()
    {
        $session = Session::getInstance();
        if ($session->isSignedIn()){
            header('Location: /feed');
        }
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function index()
    {
        View::renderTemplate('Instakilogram/index.html');
    }
}

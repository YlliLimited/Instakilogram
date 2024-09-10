<?php

namespace App\Helper;

class Session
{
  private $loadingScreen = "
      <div class='d-flex justify-content-center align-items-center position-fixed top-0 start-0' style='min-width: 100vw; min-height: 100vh; z-index: 1000000'>
          <div class='spinner-border text-primary'></div>
      </div>
  ";

  private static $instances = [];
  private $signedIn = false;
  public $userId;
  public $user;
  public $message;

  private function __construct()
  {
    session_start();
    $this->signedIn = isset($_SESSION['userId']);
    $this->checkLogedIn();
    $this->checkMessage();
  }

  private function __clone()
  {
  }

  public static function getInstance(): Session
  {
    $cls = static::class;
    if (!isset(self::$instances[$cls])) {
      self::$instances[$cls] = new static();
    }

    return self::$instances[$cls];
  }

  public function isSignedIn()
  {
    return $this->signedIn;
  }

  public function checkLogedIn()
  {
    if (isset($_SESSION['userId'])) {
      $this->userId = $_SESSION['userId'];
      $this->signedIn = true;
    } else {
      unset($this->userId);
      $this->signedIn = false;
    }
  }

  public function login($user)
  {
    if ($user) {
      $this->user = $_SESSION['user'] = $user;
      $this->userId = $_SESSION['userId'] = $user->id;
      $this->signedIn = true;
    }
  }

  public function logout()
  {
    unset($_SESSION['userId']);
    unset($_SESSION['user']);
    unset($this->userId);
    unset($this->message);
    $this->signedIn = false;
    session_destroy();
  }

  public function setMessage($message = "")
  {
    if (!empty($message)) {
      $this->message = $message;
      $_SESSION['message'] = $message;
    } else {
      return $this->message;
    }
  }

  public function checkMessage()
  {
    if (isset($_SESSION['message'])) {
      $this->message = $_SESSION['message'];
      unset($_SESSION['message']);
    } else {
      $this->message = "";
    }
  }

  public function setLoadingScreen() {
    $this->loadingScreen = "
        <div class='d-flex justify-content-center align-items-center position-fixed top-0 start-0' style='min-width: 100vw; min-height: 100vh; z-index: 1000000'>
            <div class='spinner-border text-primary'></div>
        </div>
    ";
  }
  public function loadingScreen() {
    ob_start();
    echo $this->loadingScreen;
  }

  public function stopLoadingScreen() {
    $this->loadingScreen = "";
    ob_end_clean();
  }

}
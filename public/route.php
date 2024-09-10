<?php

/**
 * Routing
 */
$router = new Core\Router();




// Add the routes 
$router->add('', ['controller' => 'HomeController', 'action' => 'index']);


//Auth routes
$router->add('login', ['controller' => 'AuthController', 'action' => 'loginPage']);
$router->add('signup', ['controller' => 'AuthController', 'action' => 'signupPage']);
$router->add('login/handler', ['controller' => 'AuthController', 'action' => 'loginUser']);
$router->add('signup/handler', ['controller' => 'AuthController', 'action' => 'signupUser']);
$router->add('signout/handler', ['controller' => 'AuthController', 'action' => 'signoutUser']);
//Auth routes end


//Profile routes
$router->add('profile', ['controller' => 'ProfileController', 'action' => 'index']);
//Profile routes end


//Notifications routes
$router->add('notifications', ['controller' => 'NotificationController', 'action' => 'index']);
$router->add('notifications/delete', ['controller' => 'NotificationController', 'action' => 'destroy']);
$router->add('notifications/clear-all', ['controller' => 'NotificationController', 'action' => 'destroyAll']);
//Notifications routes end


//Search routes
$router->add('search', ['controller' => 'SearchController', 'action' => 'index']);
$router->add('search/get', ['controller' => 'SearchController', 'action' => 'getProfiles']);
//Search routes end

//Feed routes
$router->add('feed', ['controller' => 'FeedController', 'action' => 'index']);
//Feed routes end


//Post routes
$router->add('post/create', ['controller' => 'PostController', 'action' => 'create']);
$router->add('post/edit', ['controller' => 'PostController', 'action' => 'edit']);
$router->add('post/view', ['controller' => 'PostController', 'action' => 'show']);
$router->add('post/store', ['controller' => 'PostController', 'action' => 'store']);
$router->add('post/update', ['controller' => 'PostController', 'action' => 'update']);
$router->add('post/delete', ['controller' => 'PostController', 'action' => 'destroy']);
$router->add('post/like', ['controller' => 'PostController', 'action' => 'like']);
$router->add('post/unlike', ['controller' => 'PostController', 'action' => 'unlike']);
//Post routes end


//Follow routes
$router->add('followers', ['controller' => 'FollowController', 'action' => 'followers']);
$router->add('follower/remove', ['controller' => 'FollowController', 'action' => 'removeFollower']);
$router->add('following', ['controller' => 'FollowController', 'action' => 'following']);
$router->add('following/remove', ['controller' => 'FollowController', 'action' => 'removeFollowing']);
$router->add('follow', ['controller' => 'FollowController', 'action' => 'follow']);
//Follow routes end


//Liked routes
$router->add('liked/posts', ['controller' => 'LikedController', 'action' => 'index']);
//Liked routes end


//Comments routes
$router->add('comments/view', ['controller' => 'CommentController', 'action' => 'index']);
$router->add('comments/store', ['controller' => 'CommentController', 'action' => 'store']);
$router->add('comments/delete', ['controller' => 'CommentController', 'action' => 'destroy']);
$router->add('comments/edit', ['controller' => 'CommentController', 'action' => 'edit']);
$router->add('comments/update', ['controller' => 'CommentController', 'action' => 'update']);
//Comments routes end


//Settings routes
$router->add('settings', ['controller' => 'SettingsController', 'action' => 'index']);
$router->add('settings/edit-theme', ['controller' => 'SettingsController', 'action' => 'editTheme']);
$router->add('user/delete', ['controller' => 'SettingsController', 'action' => 'destroyUser']);
$router->add('user/edit', ['controller' => 'SettingsController', 'action' => 'updateUser']);
//Settings routes end


$router->dispatch($_SERVER['QUERY_STRING']);
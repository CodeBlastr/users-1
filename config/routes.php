<?php
/**
 * Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

use Cake\Routing\Router;
use Cake\Core\Configure;

Router::plugin('Users', function ($routes) {
    $routes->fallbacks('DashedRoute');
});

Router::scope('/auth', function ($routes) {
    $routes->connect(
        '/*',
        Configure::read('Opauth.path')
    );
});
Router::connect('/accounts/validate/*', [
    'admin' => false,
    'plugin' => 'Users',
    'controller' => 'SocialAccounts',
    'action' => 'validate'
]);
Router::connect('/profile/*', ['admin' => false, 'plugin' => 'Users', 'controller' => 'Users', 'action' => 'profile']);
Router::connect('/login', ['admin' => false, 'plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
Router::connect('/logout', ['admin' => false, 'plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout']);
<?php
$routes->group('api',['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->group('ingredient', function($routes) {
        $routes->get('all', 'Ingredient::search');
    });
    $routes->group('user', function($routes) {
        $routes->get('all', 'User::search');
    });
    $routes->group('tag', function($routes) {
        $routes->get('all', 'Tag::search');
    });
});
$routes->group('api', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], function ($routes) {
    $routes->group('recipe', function($routes) {
        $routes->post('score', 'Recipe::saveScore');
        $routes->post('favorite', 'Recipe::switchFavorite');
        $routes->post('comments', 'Recipe::saveComments');
        $routes->get('get-comments', 'Recipe::getComments');
    });
});
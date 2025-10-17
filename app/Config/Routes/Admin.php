<?php
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth:administrateur'], function ($routes) {
    //Routes vers le tableau de bord
    $routes->get('dashboard', 'Admin::dashboard');

    $routes->group('user', function ($routes) {
        $routes->get('/', 'User::index');
        $routes->get('search', 'User::search');
        $routes->get('(:num)', 'User::edit/$1');
        $routes->get('new', 'User::create');
        $routes->post('update', 'User::update');
        $routes->post('insert', 'User::insert');
        $routes->post('switch-active', 'User::switchActive');
        $routes->post('delete-avatar', 'User::deleteAvatar');
    });

    $routes->group('user-permission', function ($routes) {
        $routes->get('/', 'UserPermission::index');
        $routes->post('update', 'UserPermission::update');
        $routes->post('insert', 'UserPermission::insert');
        $routes->post('delete', 'UserPermission::delete');
    });

    $routes->group('recipe', function ($routes) {
        $routes->get('/', 'Recipe::index');
        $routes->get('(:num)', 'Recipe::edit/$1');
        $routes->get('new', 'Recipe::create');
        $routes->post('insert', 'Recipe::insert');
        $routes->post('update', 'Recipe::update');
        $routes->post('switch-active', 'Recipe::switchActive');
    });

    $routes->group('brand', function ($routes) {
        $routes->get('search', 'Brand::search');
        $routes->get('/', 'Brand::index');
        $routes->post('update', 'Brand::update');
        $routes->post('insert', 'Brand::insert');
        $routes->post('delete', 'Brand::delete');
    });

    $routes->group('ingredient', function ($routes) {
        $routes->get('search', 'Ingredient::search');
        $routes->get('/', 'Ingredient::index');
        $routes->get('(:num)', 'Ingredient::edit/$1');
        $routes->get('new', 'Ingredient::create');
        $routes->post('insert', 'Ingredient::insert');
        $routes->post('update', 'Ingredient::update');
        $routes->post('delete', 'Ingredient::delete');
    });

    $routes->group('categ-ing', function ($routes) {
        $routes->get('search', 'CategIng::search');
        $routes->get('/', 'CategIng::index');
        $routes->post('update', 'CategIing::update');
        $routes->post('insert', 'CategIng::insert');
        $routes->post('delete', 'CategIng::delete');
    });

    $routes->group('unit', function ($routes) {
        $routes->get('search', 'Unit::search');
        $routes->get('/', 'Unit::index');
        $routes->post('update', 'Unit::update');
        $routes->post('insert', 'Unit::insert');
        $routes->post('delete', 'Unit::delete');

    });

    $routes->group('tag', function ($routes) {
        $routes->get('search', 'Tag::search');
        $routes->get('/', 'Tag::index');
        $routes->post('update', 'Tag::update');
        $routes->post('insert', 'Tag::insert');
        $routes->post('delete', 'Tag::delete');
    });

    $routes->group('substitute', function ($routes) {
        $routes->get('search', 'Substitute::search');
        $routes->post('update', 'Substitute::update');
        $routes->post('insert', 'Substitute::insert');
        $routes->post('delete', 'Substitute::delete');
    });

    $routes->group('media', function ($routes) {
        $routes->get('/', 'Media::index');
    });
});
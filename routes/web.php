<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API route group
$router->group(['prefix' => 'user'], function () use ($router) {
    // Matches "/api/register
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->get('/badge/get', 'BadgeController@getUserBadge');
    $router->get('/certificate/get', 'CertificateController@getUserCertificate');
    $router->get('/payment/get', 'PaymentController@getUserPayment');
    $router->get('/payment/create', 'PaymentController@makePayment');
    $router->get('/dashbord/get', 'AuthController@getUserDashboard');
});

$router->group(['prefix' => 'dashboard', 'middleware' => 'user'], function () use ($router) {
    $router->get('user', 'DashboardController@getUserDashboard');
});

$router->group(['prefix' => 'badge'], function () use ($router) {
    $router->post('create', 'BadgeController@create');
    $router->put('edit/{id}', 'BadgeController@edit');
    $router->delete('delete/{id}', 'BadgeController@delete');
    $router->get('getAll', 'BadgeController@getAllRecords');
});


$router->group(['prefix' => 'exam'], function () use ($router) {
    $router->post('create', 'ExamController@create');
    $router->put('edit/{id}', 'ExamController@edit');
    $router->delete('delete/{id}', 'ExamController@delete');
    $router->get('getAll', 'ExamController@getAllRecords');
});


$router->group(['prefix' => 'badge_uploaded','middleware' => 'admin'], function () use ($router) {
    $router->post('create/{id}', 'BadgeUploadedController@Create');
    $router->get('get/{id}', 'BadgeUploadedController@getAll');
    $router->get('view/{id}', 'BadgeUploadedController@view');
});


$router->group(['prefix' => 'payment','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'PaymentController@Create');
    $router->get('get', 'PaymentController@getAll');
    $router->get('view/{id}', 'PaymentController@view');
    $router->put('edit/{id}', 'PaymentController@edit');
    $router->delete('delete/{id}', 'PaymentController@delete');
});


$router->group(['prefix' => 'subject', 'middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'SubjectController@Create');
    $router->get('get', 'SubjectController@getAll');
    $router->get('view/{id}', 'SubjectController@view');
    $router->put('edit/{id}', 'SubjectController@edit');
    $router->delete('delete/{id}', 'SubjectController@delete');
});

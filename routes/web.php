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
    $router->post('/certificate/create', 'CertificateController@create');
    $router->delete('/certificate/delete/{id}', 'CertificateController@delete');
    $router->get('/certificate/get-certificates', 'CertificateController@getuserCertificates');
    $router->get('/certificate/{id}', 'CertificateController@getuserSingleCertificate');
    $router->get('/paid/get', 'PaymentController@getAllUserPayment');
    $router->get('/pending-payment/get', 'PaymentController@getAllUserPendingPayment');
    $router->get('/regular-payment-status/get', 'PaymentController@getUserRegularPaymentStatus');
    $router->get('/payment/create', 'PaymentController@makePayment');
    $router->get('/dashboard', 'DashboardController@getUserDashboard');
});

//$router->group(['prefix' => 'dashboard'], function () use ($router) {
//    $router->get('user', 'DashboardController@getUserDashboard');
//});

$router->group(['prefix' => 'dashboard', 'middleware' => 'admin'], function () use ($router) {
    $router->get('admin', 'DashboardController@getAdminDashboard');
});

$router->group(['prefix' => 'badge','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'BadgeController@create');
    $router->post('edit/{id}', 'BadgeController@edit');
    $router->delete('delete/{id}', 'BadgeController@delete');
    $router->get('getAll', 'BadgeController@getAllRecords');
});


$router->group(['prefix' => 'exam','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'ExamController@create');
    $router->post('edit/{id}', 'ExamController@edit');
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


$router->group(['prefix' => 'admin/user','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'UserController@Create');
    $router->get('get', 'UserController@getAllUser');
    $router->get('view/{id}', 'UserController@getUser');
    $router->put('edit/{id}', 'UserController@edit');
    $router->delete('delete/{id}', 'UserController@delete');
    $router->post('file-upload', 'UserController@fileUploadTest');
});


$router->group(['prefix' => 'admin/exempt-exam','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'ExamExemptController@create');
    $router->get('get', 'ExamExemptController@get');
});

$router->group(['prefix' => 'admin/settings','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'SettingsController@create');
    $router->get('get', 'SettingsController@get');
    $router->put('edit/{id}', 'SettingsController@edit');
    $router->put('enable-disbale/{id}', 'SettingsController@enableDisable');
});

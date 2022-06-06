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
    $router->get('/badge/get', 'BadgeUploadedController@getUserBadge');
    $router->post('/certificate/create', 'CertificateController@create');
    $router->delete('/certificate/delete/{id}', 'CertificateController@delete');
    $router->get('/certificate/get-certificates', 'CertificateController@getuserCertificates');
    $router->get('/certificate/view/{id}', 'CertificateController@getuserSingleCertificate');
    $router->get('/paid/get', 'PaymentController@getAllUserPayment');
    $router->get('/pending-payment/get', 'PaymentController@getAllUserPendingPayment');
    $router->get('/regular-payment-status/get', 'PaymentController@getUserRegularPaymentStatus');
    $router->post('/payment/create', 'PaymentController@makePayment');
    $router->get('/dashboard', 'DashboardController@getUserDashboard');
    $router->get('/certificate/get-certificates-admin/{id}', 'CertificateController@getuserCertificatesAdmin');
});

$router->post('logout', 'AuthController@logout');

$router->get('get-image', 'AuthController@getImage');

$router->group(['prefix' => 'dashboard'], function () use ($router) {
    $router->get('user', 'DashboardController@getUserDashboard');
});

$router->group(['prefix' => 'dashboard', 'middleware' => 'admin'], function () use ($router) {
    $router->get('admin', 'DashboardController@getAdminDashboard');
});

$router->group(['prefix' => 'badge','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'BadgeController@create');
    $router->post('edit/{id}', 'BadgeController@edit');
    $router->delete('delete/{id}', 'BadgeController@delete');
    $router->get('get-all', 'BadgeController@getAllRecords');
    $router->get('view/{id}', 'BadgeController@view');
});


$router->group(['prefix' => 'exam','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'ExamController@create');
    $router->post('edit/{id}', 'ExamController@edit');
    $router->delete('delete/{id}', 'ExamController@delete');
    $router->get('get-all', 'ExamController@getAllRecords');
});


$router->group(['prefix' => 'badge_uploaded','middleware' => 'admin'], function () use ($router) {
    $router->post('create/{id}', 'BadgeUploadedController@Create');
    $router->get('get/{id}', 'BadgeUploadedController@getAll');
    $router->get('view/{id}', 'BadgeUploadedController@view');
});


$router->group(['prefix' => 'payment','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'PaymentController@Create');
    $router->get('get-all', 'PaymentController@getAllRecords');
    $router->get('view/{id}', 'PaymentController@view');
    $router->put('edit/{id}', 'PaymentController@edit');
    $router->delete('delete/{id}', 'PaymentController@delete');
});


$router->group(['prefix' => 'subject', 'middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'SubjectController@Create');
    $router->get('get-all', 'SubjectController@getAllRecords');
    $router->get('view/{id}', 'SubjectController@view');
    $router->put('edit/{id}', 'SubjectController@edit');
    $router->delete('delete/{id}', 'SubjectController@delete');
});


$router->group(['prefix' => 'admin/user'], function () use ($router) {
    $router->post('create', 'UserController@Create');
    $router->get('get', 'UserController@getAllUser');
    $router->get('view/{id}', 'UserController@getUser');
    $router->post('edit/{id}', 'UserController@edit');
    $router->delete('delete/{id}', 'UserController@delete');
    $router->post('file-upload', 'UserController@fileUploadTest');
    //$router->post('filter', 'UserController@getUserByAccountType');
    $router->post('search', 'UserController@getUserSearch');
});


$router->group(['prefix' => 'admin/exempt-exam','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'ExamExemptController@create');
    $router->get('get/{id}', 'ExamExemptController@get');
});

$router->group(['prefix' => 'admin/settings','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'SettingsController@create');
    $router->get('get', 'SettingsController@get');
    $router->put('edit/{id}', 'SettingsController@edit');
    $router->put('enable-disbale/{id}', 'SettingsController@enableDisable');
});

$router->group(['prefix' => 'admin/honorary','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'HonoraryController@create');
    $router->get('get-all', 'HonoraryController@getAll');
    $router->get('get/{id}', 'HonoraryController@get');
    $router->put('edit/{id}', 'HonoraryController@edit');
    $router->delete('delete/{id}', 'HonoraryController@delete');
});

$router->group(['prefix' => 'admin/induction','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'InductionController@create');
    $router->get('get-all', 'InductionController@getAll');
    $router->get('get/{id}', 'InductionController@get');
    $router->put('edit/{id}', 'InductionController@edit');
    $router->delete('delete/{id}', 'InductionController@delete');
});

$router->group(['prefix' => 'admin/payment-settings','middleware' => 'admin'], function () use ($router) {
    $router->post('create', 'PaymentSettingsController@create');
    $router->get('get-all', 'PaymentSettingsController@getAll');
    $router->put('edit/{id}', 'PaymentSettingsController@edit');
    $router->delete('delete/{id}', 'PaymentSettingsController@delete');
});

$router->group(['prefix' => 'user/payment-settings'], function () use ($router) {
    $router->get('get/{id}', 'PaymentSettingsController@get');
});



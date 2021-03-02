<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::view('/welcome', 'welcome');

Auth::routes();
Route::match(["GET", "POST"], "/register", function(){
    return redirect("/login");
})->name("register");


Route::get('/', 'HomeController@index');


Route::resource('products','ProductController@index')->except(['show','update']);
Route::get('/products', 'ProductController@index');

Route::get('/traffic_jaringan', 'TaskController@traffic_jaringan')->name('laporan.traffic_jaringan');

// Route::resource('status_perangkat', 'LaporanController');
// Route::resource('status_perangkat','LaporanController@status_perangkat')->except(['show','update']);
Route::resource('laporan', 'LaporanController');
Route::get('/status_perangkat', 'LaporanController@status_perangkat')->name('laporan.status_perangkat');
Route::get('/traffic_jaringan', 'LaporanController@traffic_jaringan')->name('laporan.traffic_jaringan');
Route::get('/traffic_jaringan.perangkat', 'LaporanController@traffic_jaringan')->name('laporan.traffic_jaringan');
// Route::get('id', 'LaporanController@traffic_jaringan')->name('laporan.traffic_jaringan');
// Route::get('monitoring/grafik/suhu', 'DashboardController@grafikSuhu')->name('monitoring.grafik.suhu');
// Route::get('/laporan/show', 'LaporanController@grafik_traffic')->name('laporan.show');


// services general ajax
Route::prefix('services')->group(function() {
    Route::get('drivers', 'ServicesController@drivers')->name('services.drivers');
    Route::get('routes', 'ServicesController@routes')->name('services.routes');
    Route::get('gedung', 'ServicesController@gedung')->name('services.gedung');
    Route::get('inventories', 'ServicesController@inventories')->name('services.inventories');
    // Route::get('perangkat', 'PerangkatController@perangkat')->name('services.sales');
    Route::get('preorders', 'ServicesController@preorders')->name('services.preorders');
    Route::get('purchases', 'ServicesController@purchases')->name('services.purchases');
    Route::get('shippings', 'ServicesController@shippings')->name('services.shippings');
});

// masters
Route::resource('perangkat', 'PerangkatController');
// Route::prefix('perangkat')->group(function(){
//     Route::get('create/{type}', 'PerangkatController@create');
//     Route::get('{id}/edit', 'PerangkatController@edit');
// });

Route::resource('users', 'UserController');
Route::resource('gedung', 'GedungController');
 
Route::resource('routes', 'RoutesController');
// Route::resource('salesdirects', 'SalesDirectsController');


// logStatus


// sales = logstatus
Route::resource('logstatus', 'LogstatusController');
Route::prefix('logstatus')->group(function(){
    // Route::get('{id}/payments', 'SalesController@payments')->name('sales.payments');
    // Route::post('storepayments', 'SalesController@storepayments')->name('sales.storepayments');
});

//preorder
Route::resource('preorder', 'PreordersController');


// reports
Route::prefix('reports')->group(function(){
    Route::get('inventories', 'InventoriesController@report')->name('inventories.reports');
    // Route::get('sales', 'SalesController@report')->name('sales.reports');

    Route::get('purchases', 'PurchasesController@report')->name('purchases.reports');
    Route::get('shippings', 'ShippingsController@report')->name('shippings.reports');
    
});
Route::resource('reports', 'ReportsController');


//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

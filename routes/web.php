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

Route::get('/',function()
{
    return redirect('home');
});

Route::get('/postarticle',function()
{
    return view('postarticle');
})->middleware('auth');

Route::get('/postjob',function()
{
    return view('postjob');
})->middleware('adminonly');

Route::get('/allukm',function()
{
    return view('allukm');
})->middleware('adminonly');

Route::get('/addukm',function()
{
    return view('addukm');
})->middleware('adminonly');

Route::get('/homeall',function()
{
    return view('homeall');
})->middleware('auth');

Route::get('/home',function()
{
    return view('home');
})->middleware('auth');

Route::get('/register',function()
{
    return view('register');
})->middleware('guest');

Route::get('/articles',function()
{
    return view('articles');
})->middleware('auth');

Route::get('/searchartikel',function()
{
    return view('searchartikel');
})->middleware('auth');

Route::get('/alljobs',function()
{
    return view('alljobs');
})->middleware('auth');

Route::get('/setting',function()
{
    return view('setting');
})->middleware('auth');

Route::get('/profile',function()
{
    return view('profile');
})->middleware('auth');

Route::get('/users',function()
{
    return view('users');
})->middleware('adminonly');

Route::get('/registerusers',function()
{
    return view('registerusers');
})->middleware('adminonly');

Route::get('/categoryarticles',function()
{
    return view('categoryarticles');
})->middleware('adminonly');

Route::get('/jurusans',function()
{
    return view('jurusans');
})->middleware('adminonly');

Route::post('/registeruser','RegisteruserController@store')->middleware('guest');

Route::post('/registeruser/delete/{registeruser}','RegisteruserController@decline')->middleware('adminonly');

Route::get('/unvalidateemail/{token}','RegisteruserController@notme');

Route::get('/validateconfirmed/{token}','RegisteruserController@activate');

Route::get('/notme/{token}','VerifyuserController@notme');

Route::get('/activate/{token}','VerifyuserController@activate');

Route::get('/viewallfollows/{username}','FollowController@viewallfollow')->middleware('auth');

Route::get('/viewallmembers/{slug}','UkmanggotaController@viewallmember')->middleware('auth');

Route::get('/getmorenotif/{notif_id}', 'NotificationController@morenotif')->middleware('auth');

Route::put('/accept/{ukmanggota}','UkmanggotaController@accept')->middleware('auth');

Route::put('/decline/{ukmanggota}','UkmanggotaController@decline')->middleware('auth');

Route::post('/ukm','UkmController@store')->middleware('adminonly');

Route::get('/getukm/{id}', 'UkmController@getukm')->middleware('auth');

Route::post('/changeukmimage/{ukm}','UkmController@changeimage')->middleware('auth');

Route::post('/changeukmcoverimage/{ukm}','UkmController@changecover')->middleware('auth');

Route::post('/changeukmleader/{ukm}','UkmController@changeleader')->middleware('auth');

Route::post('/joinukm','UkmanggotaController@store')->middleware('auth');

Route::post('/jurusan','JurusanController@store')->middleware('adminonly');

Route::put('/jurusan/update/{jurusan}','JurusanController@update')->middleware('adminonly');

Route::post('/categoryarticle','ArtikelcategoryController@store')->middleware('adminonly');

Route::put('/categoryarticle/update/{artikelcategory}','ArtikelcategoryController@update')->middleware('adminonly');

Route::post('/user','UserController@store')->middleware('adminonly');

Route::put('/user/update/{user}','UserController@update')->middleware('adminonly');

Route::put('/changepassword','UserController@changepassword')->middleware('auth');

Route::post('/user/hide/{user}','UserController@hide')->middleware('adminonly');

Route::post('/user/unhide/{user}','UserController@unhide')->middleware('adminonly');

Route::post('/job','JobController@store')->middleware('adminonly');

Route::get('/job/{job}/edit','JobController@edit')->middleware('adminonly');

Route::put('/job/{job}/edit','JobController@update')->middleware('adminonly');

Route::delete('/job/{job}','JobController@destroy')->middleware('adminonly');

Route::post('/article','ArtikelController@store')->middleware('auth');

Route::get('/article/{artikel}/edit','ArtikelController@edit')->middleware('auth');

Route::put('/article/{artikel}/edit','ArtikelController@update')->middleware('auth');

Route::delete('/article/{artikel}','ArtikelController@destroy')->middleware('auth');

Route::post('/status','StatusController@store')->middleware('auth');

Route::post('/statusukm/{ukm}','StatusController@storeukm')->middleware('auth');

Route::get('/getukmstatus/{id}/{status_id}', 'UkmContoller@getukmstatus')->middleware('auth');

Route::delete('/status/{status}','StatusController@destroy')->middleware('auth');

Route::post('/readmessage','MessageController@read')->middleware('auth');

Route::post('/sendmessage','MessageController@store')->middleware('auth');

Route::post('/likestatus','LikeController@store')->middleware('auth');

Route::post('/likearticle','LikeartikelController@store')->middleware('auth');

Route::post('/comment','KomenstatusController@store')->middleware('auth');

Route::post('/commentarticle','KomenartikelController@store')->middleware('auth');

Route::get('/allmessage','MessageController@allmessage')->middleware('auth');

Route::get('/search','UserController@search')->middleware('auth');

Route::get('/login', function () {
    return view('/login');
});

Route::get('/ukmprofile/{id}','UkmController@view')->middleware('auth');

Route::get('/viewarticle/{id}', 'ArtikelController@view')->middleware('auth');

Route::get('/viewjob/{id}', 'JobController@view')->middleware('auth');

Route::get('/viewprofile/{id}', 'UserController@View')->middleware('auth');

Route::get('/viewmessage/{id}', 'MessageController@view')->middleware('auth');

Route::get('/viewstatus/{id}', 'StatusController@view')->middleware('auth');

Route::get('/getallstatus/{id}', 'StatusController@getallstatus')->middleware('auth');

Route::get('/getuserstatus/{id}/{status_id}', 'StatusController@getuserstatus')->middleware('auth');

Route::get('/getstatus/{id}', 'StatusController@getstatus')->middleware('auth');

Route::post('/changeprofileimage','UserController@changeimage')->middleware('auth');

Route::post('/changeprofilecoverimage','UserController@changecover')->middleware('auth');

Route::post('/follow', 'FollowController@store')->middleware('auth');

Route::get('/getnewstatus/{id}', 'StatusController@getnewstatus')->middleware('auth');

Route::get('/getcountstatus/{id}', 'StatusController@getcountstatus')->middleware('auth');

Route::get('/getuserarticle/{id}/{artikel_id}', 'ArtikelController@getuserarticle')->middleware('auth');

Route::get('/getarticle/{id}', 'ArtikelController@getarticle')->middleware('auth');

Route::get('/getjob/{id}', 'JobController@getjob')->middleware('auth');

Route::get('/getkomenarticle/{id}/{idkomen}', 'KomenartikelController@getkomenarticle')->middleware('auth');

Route::get('/getnewallstatus/{id}', 'StatusController@getnewallstatus')->middleware('auth');

Route::get('/getcountallstatus/{id}', 'StatusController@getcountallstatus')->middleware('auth');

Route::get('/getnotif/{id}', 'NotificationController@getnotif')->middleware('auth');

Route::get('/getkomenstatus/{id}/{idkomen}', 'KomenstatusController@getkomenstatus')->middleware('auth');

Route::get('/getmessage/{id}', 'MessageController@getmessage')->middleware('auth');

Route::get('/loadmoremessage/{nickname}/{id}', 'MessageController@loadmoremessage')->middleware('auth');

Auth::routes();

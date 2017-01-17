<?php
namespace App\init;

use Candy\core\Route;
use Candy\core\Error;

Route::get('404', [new Error(), 'show'], ['code'=>404, 'msg'=>'页面未找到']);

// WellcommeController
Route::get('/wellcome', ['wellcome', 'index']);
Route::get('/account/any:name', ['account', 'index']);
Route::get('/signin', ['common\security', 'signin']);
Route::post('/signin', ['common\security', 'signin']);
Route::get('/signup', ['common\security', 'signup']);
Route::get('/signout', ['common\security', 'signout']);
// HomepageController
Route::get('/home/num:page?', ['homepage', 'index']);
Route::get('/num:page?', ['homepage', 'index']);
// ContentController
Route::get('/search/num:page?', ['content', 'search']);
Route::get('/article/any:slug', ['content', 'view'], ['type'=>'post']);
Route::get('/articles/num:page?', ['content', 'index'], ['type'=>'post']);
Route::get('/category/any:slug/num:page?', ['content', 'meta'], ['type'=>'category']);
Route::get('/tag/any:slug/num:page?', ['content', 'meta'], ['type'=>'tag']);
// dashboard/OverviewController
Route::get('/dashboard', ['dashboard/overview', 'index']);
// dashboard/ContentController
Route::get('/dashboard/pages/num:page?', ['dashboard/content', 'index'], ['type'=>'page']);
Route::get('/dashboard/page/add', ['dashboard/content', 'form'], ['type'=>'page']);
Route::post('/dashboard/page/add', ['dashboard/content', 'create'], ['type'=>'page']);
Route::get('/dashboard/page/edit/num:cid', ['dashboard/content', 'form'], ['type'=>'page']);
Route::post('/dashboard/page/edit/num:cid', ['dashboard/content', 'updata'], ['type'=>'page']);
// dashboard/ContentController
Route::get('/dashboard/articles/num:page?', ['dashboard/content', 'index'], ['type'=>'post']);
Route::get('/dashboard/article/add', ['dashboard/content', 'form'], ['type'=>'post']);
Route::post('/dashboard/article/add', ['dashboard/content', 'create'], ['type'=>'post']);
Route::get('/dashboard/article/edit/num:cid', ['dashboard/content', 'form'], ['type'=>'post']);
Route::post('/dashboard/article/edit/num:cid', ['dashboard/content', 'updata'], ['type'=>'post']);
// ContentController
Route::get('/any:slug', ['content', 'view'], ['type'=>'page']);

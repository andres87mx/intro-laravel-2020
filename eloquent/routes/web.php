<?php

use Illuminate\Support\Facades\Route;

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
use App\Post;

Route::get('eloquent', function () {
    $posts = Post::where('id','>=','20')->orderBy('id','desc')->take(3)->get();
    foreach ($posts as $post) {
        echo "$post->id $post->titulo <br>";
    }
});

Route::get('posts', function () {
    $posts = Post::get();
    foreach ($posts as $post) {
        echo "
            $post->id
            <strong>{$post->user->name} </strong>
            $post->title <br>";
    }
});

use App\User;
Route::get('users', function () {
    $users = User::get();
    foreach ($users as $user) {
        echo "
            $user->id
            $user->name 
            {$user->posts->count()} <br>";
    }
});

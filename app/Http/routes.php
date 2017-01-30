<?php

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
use Illuminate\Http\Request;
use App\Book;
$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/books','BooksController@index');
//$app->get('/books/{id}','BooksController@show');
//$app->post('/books','BooksController@store');
$app->post('/books',function(Request $request){
	$this->validate($request,[
		'title'=>'required|max:10',
		'description'=>'required',
		'author_id'=>'required|exists:authors,id'
	],[
		'description.required'=>'Please provide a :attribute.'
	]);
	$book = Book::create($request ->all());
	return response()->json(['data'=>$book->toArray()],201,[
		'Location' => route('books.show',['id'=>$book->id])
	]);
});
$app->get('books/{id:[\d]+}',[
	'as'=>'books.show',
	'uses'=>'BooksController@show']);
$app->put('/books/{id:[\d]+}','BooksController@update');
$app->delete('/books/{id:[\d]+}','BooksController@destroy');
$app->group(['prefix'=>'/authors','namespace'=>'App\Http\Controllers'],
	function($app){
		$app->get('/','AuthorsController@index');
		$app->post('/','AuthorsController@store');
		$app->get('/{id:[\d]+}','AuthorsController@show');
		$app->put('/{id:[\d]+}','AuthorsController@update');
		$app->delete('/{id:[\d]+}','AuthorsController@destroy');

		//Author Ratings
		$app->post('/{id:[\d]+}/ratings','AuthorsRatingsController@store');
		$app->delete('/{authorId:[\d]+}/ratings/{ratingId:[\d]+}','AuthorsRatingsController@destroy');
		$app->get('/{id:[\d]+}/ratings','AuthorsRatingsController@show');
	});
	$app->group(['prefix'=>'/bundles','namespace'=>'App\Http\Controllers'],function($app){
	$app->get('/{id:[\d]+}',[
		'as'=>'bundles.show',
		'uses'=>'BundlesController@show'
	]);
	$app->put('/{bundleId:[\d]+}/books/{bookId:[\d+]}','BundlesController@addBook');
	$app->delete('/{bundleId:[\d]+}/books/{bookId:[\d]+}','BundlesController@removeBook');
});


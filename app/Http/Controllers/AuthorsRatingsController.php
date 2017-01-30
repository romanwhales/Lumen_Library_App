<?php
	namespace App\Http\Controllers;

	use App\Author;
	use Illuminate\Http\Request;
	/**
	 * Manage an Author's Ratings
	 */
	class AuthorsRatingsController extends Controller{
		public function store(Request $request,$authorId){
			$author = Author::findOrFail($authorId);

			$rating = $author->ratings()->create(['value'=>$request->get('value')]);
			return response()->json($rating,201);
		}
		public function destroy($authorId,$ratingId){
			/**
			 @var \App\Author $author
			*/
			$author = Author::findOrFail($authorId);
			$author->ratings()->findOrFail($ratingId)->delete();
			return response(null,204);
		}
		public function show($authorId){
			$author = Author::findOrFail($authorId);
			$name = $author->name;
			$ratingsCount = $author->ratings->count();
			$average = (float)sprintf("%.2f",$author->ratings->avg('value'));
			return['Count'=>$ratingsCount,'Average'=>$average,'Name'=>$name];
			//print_r($ratings);
		}
	}
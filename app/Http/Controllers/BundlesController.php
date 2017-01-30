<?php
	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	use App\Bundle;
	use App\Book;

	class BundlesController extends Controller{
		public function show($id){
			$bundle = Bundle::findOrFail($id);
			$arr =[];
			foreach($bundle->books as $book){
				array_push($arr,$book->author);
			}
			//print_r($bundle->books);
			return ['bundles'=>$bundle];
			//return ['data'=>$arr];
		}
		/**
		 *@param int $bundleId
		 *@param int $bookId
		 *@return \Illuminate\Http\Response
		 */
		public function addBook($bundleId,$bookId){
			$bundle=\App\Bundle::findOrFail($bundleId);
			$book = \App\Book::findOrFail($bookId);
			$bundle->books()->attach($book);
			return ['data'=>$bundle];
		}
		public function removeBook($bundleId,$bookId){
			$bundle = \App\Bundle::findOrFail($bundleId);
			$book = \App\Book::findOrFail($bookId);
			$bundle->books()->detach($book);
			return response(null,204);
		}
	}
<?php
	namespace App\Http\Controllers;
	
	use App\Book;
	use Illuminate\Http\Request;
	use Illuminate\Database\Eloquent\ModelNotFoundException;

	/**
	 * Class BooksController
	 * @package App\Http\Controllers
	 */
	class BooksController{
		/**
		* GET /books
		* @return array
		*/
		public function index(){
			//Eager loading ish makes sense
			//$b = Book::with('author')->get();
			//return Book::all();
			$books = Book::all();
			$b = [];
			foreach ($books as $book) {
				array_push($b,[
				'id' => $book->id,
				'title' => $book->title,
				'description' => $book->description,
				'author' => $book->author->name, // Check the author's name
				'author_gender'=>$book->author->gender, // Check the author's gender
				'created' => $book->created_at->toIso8601String(),
				'updated' => $book->updated_at->toIso8601String(),
				]);
			}
			return $b;
			/*return['data'=>Book::all()->toArray(),
					'status'=>true
			];*/
		}
		/**
		* GET /books/{id}
		* @param integer $id
		* @return integer$id
		* @return mixed
		*/
		public function show($id){
			try{
				return ['data'=>Book::findOrFail($id)->toArray()];
			}catch(ModelNotFoundException $e){
				return response()->json([
						'error'=>[
							'message'=>'Book not found'
						]
					],404);
			}	
		}
		/** 
		 * POST /books
		 * @param Request $request
		 * @return \Symfony\Component\HttpFoundation\Response
		 */
		public function store(Request $request){
			/*try{
				$book = Book::create($request->all());
			}catch(\Exception $e){
				dd(get_class($e));
			}*/
			$this->validate($request,[
				'title'=>'required',
				'description'=>'required',
				'author'=>'required'
				]);
			$book = Book::create($request ->all());

			return response()->json(['data'=>$book->toArray()],201,[
					'Location' => route('books.show',['id'=>$book->id])
				]);
			//return response()->json(['created'=>true],201,['Location'=>route('books.show',['id'=>$book->id])]);
		}
		/**
		 * PUT /books/{id}
		 *
		 *@param Request $request
		 *@param id
		 *@return mixed
		 */
		public function update(Request $request,$id){
			try{
				$book = Book::findOrFail($id);
			}
			catch(ModelNotFoundException $e){
				return response()->json([
						'error'=>[
							'message'=>'Book not found'
						]
					],404);
			}
			$book->fill($request->all());
			$book->save();
			return ['data'=>$book->toArray()];
		}

			/*
		* DELETE /books/{id}
		*@param $id
		* @return \Illuminate\http\JsonResponse
		*/
		public function destroy($id){
			try{
				$book = Book::findOrFail($id);
			}
			catch(ModelNotFoundException $e){
				return response()->json([
						'error'=>[
							'message'=>'Book not found'
						]
					],404);
			}
			$book->delete();
			return response(null,204);
		}
	}
	
<?php
	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	use App\Author;

	class AuthorsController extends Controller{
		public function index(){
			return Author::all();
		}
		/**
		* GET /author/{id}
		* @param integer $id
		* @return integer$id
		* @return mixed
		*/
		public function show($id){
			try{
				return ['data'=>Author::findOrFail($id)->toArray()];
			}catch(ModelNotFoundException $e){
				return response()->json([
						'error'=>[
							'message'=>'Book not found'
						]
					],404);
			}	
		}
		/** 
		 * POST /authors
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
				'name'=>'required',
				'gender'=>'required',
				'biography'=>'required'
				]);
			$author = Author::create($request ->all());
			return response()->json($author,201);
		}
		/**
		 * PUT /authors/{id}
		 *
		 *@param Request $request
		 *@param id
		 *@return mixed
		 */
		public function update(Request $request,$id){
			try{
				$author = Author::findOrFail($id);
			}
			catch(ModelNotFoundException $e){
				return response()->json([
						'error'=>[
							'message'=>'author not found'
						]
					],404);
			}
			$author->fill($request->all());
			$author->save();
			return ['data'=>$author->toArray()];
		}
			/*
		* DELETE /books/{id}
		*@param $id
		* @return \Illuminate\http\JsonResponse
		*/
		public function destroy($id){
			try{
				$author = Author::findOrFail($id);
			}
			catch(ModelNotFoundException $e){
				return response()->json([
						'error'=>[
							'message'=>'Book not found'
						]
					],404);
			}
			$author->delete();
			return response(null,204);
		}
	}
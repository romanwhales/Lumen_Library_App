<?php
	
	namespace Test\App\Http\Controllers;

	use TestCase;
	use Illuminate\Http\Response;
	use Laravel\Lumen\Testing\DatabaseMigrations;

	class BooksControllerValidationTest extends TestCase
	{
		use DatabaseMigrations;

		/** @test **/
		public function it_validates_required_fields_when_creating_a_new_book()
		{
			$this->post('/books',[],['Accept'=>'application/json']);

			$this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY,$this->response->getStatusCode());

			$body = json_decode($this->response->getContent(),true);
			$this->assertArrayHasKey('title',$body);
			$this->assertArrayHasKey('description',$body);
			$this->assertArrayHasKey('description',$body);

			$this->assertEquals(["The title field is required."],$body['title']);
			$this->assertEquals(["The description field is required."],$body['description']);
			$this->assertEquals(["The author field is required."],$body['author']);
		}


	}
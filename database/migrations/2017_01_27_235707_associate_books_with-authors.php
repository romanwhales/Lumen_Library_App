<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssociateBooksWithAuthors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            //Create the author_id column as an unsigned integer
            $table->integer('author_id')->after('id')->unsigned();

            //Create a basic index for the author_id column
            $table->index('author_id');

            //create a foreign key contraint and cascade on delete,
            $table
                ->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            //Drop the foreign key first
            $table->dropForeign('books_author_id_foreign');

            //now drop the basic index
            $table->dropIndex('books_author_id_index');

            //lastly, now it is safe to drop the columns
            $table->dropColumn('author_id');
        });
    }
}

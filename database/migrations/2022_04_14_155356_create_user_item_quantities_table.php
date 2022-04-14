<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserItemQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_item_quantities', function (Blueprint $table) {
            $table->integer('user_store_no')->unsigned();
            $table->string('item_no', 50)->collation('Arabic_CI_AS');
            $table->primary(array('user_store_no', 'item_no'));

            $table->integer('available_qty')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_item_quantities');
    }
}

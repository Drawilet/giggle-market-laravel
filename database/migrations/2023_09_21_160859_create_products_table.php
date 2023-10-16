<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("store_id");
            $table->foreign("store_id")->references("id")->on("stores");

            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");

            $table->string("photo");

            $table->string("description", 50);
            $table->decimal("price");
            $table->integer("stock");

            $table->unsignedBigInteger("category_id");
            $table->foreign("category_id")->references("id")->on("categories");

            $table->boolean("unpublished")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};

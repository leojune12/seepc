<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publication_id');
            $table->string('motherboard', 255)->nullable();
            $table->string('cpu', 255)->nullable();
            $table->string('ram', 255)->nullable();
            $table->string('graphics', 255)->nullable();
            $table->string('storage', 255)->nullable();
            $table->string('display', 255)->nullable();
            $table->string('keyboard', 255)->nullable();
            $table->string('mouse', 255)->nullable();
            $table->timestamps();

            $table->foreign('publication_id')
                ->references('id')
                ->on('publications')
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
        Schema::dropIfExists('specifications');
    }
}

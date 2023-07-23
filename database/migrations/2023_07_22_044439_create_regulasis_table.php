<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegulasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulasis', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('deskripsi')->nullable();
            $table->dateTime('tanggal_awal')->nullable();
            $table->dateTime('tanggal_akhir')->nullable();
            $table->string('file_regulasi')->nullable();
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
        Schema::dropIfExists('regulasis');
    }
}

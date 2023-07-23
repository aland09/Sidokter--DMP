<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCeklisToDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->string('ceklis_seluruh')->nullable();
            $table->dateTime('tanggal_ceklis_seluruh')->nullable();
            $table->string('ceklis_satuan')->nullable();
            $table->dateTime('tanggal_ceklis_satuan')->nullable();
            $table->string('ceklis_kerja')->nullable();
            $table->dateTime('tanggal_ceklis_kerja')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            //
        });
    }
}

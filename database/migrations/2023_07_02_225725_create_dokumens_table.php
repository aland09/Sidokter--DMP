<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('no_sp2d')->unique()->nullable();
            $table->string('kode_klasifikasi')->nullable();
            $table->text('uraian')->nullable();
            $table->dateTime('tanggal_validasi')->nullable();
            $table->string('jumlah_satuan_item')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('no_spm')->nullable();
            $table->bigInteger('nominal')->nullable();
            $table->string('skpd')->nullable();
            $table->string('pejabat_penandatangan')->nullable();
            $table->string('unit_pengolah')->nullable();
            $table->integer('kurun_waktu')->nullable();
            $table->string('jumlah_satuan_berkas')->nullable();
            $table->string('tkt_perkemb')->nullable();
            $table->string('no_box')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('dokumens');
    }
}

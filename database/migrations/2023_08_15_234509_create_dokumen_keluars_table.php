<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenKeluarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_keluars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokumen_id')->nullable();
            $table->string('nama_peminjam')->nullable();
            $table->date('tanggal_peminjaman')->nullable();
            $table->string('instansi')->nullable();
            $table->text('tujuan')->nullable();
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
        Schema::dropIfExists('dokumen_keluars');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('locked')->default(false); // Menambahkan kolom locked
        });
    }
    
    public function down()
    {
        if (Schema::hasColumn('orders', 'locked')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('locked'); // Hapus kolom locked jika ada
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('markers', function (Blueprint $table) {
            // Kita tidak perlu menambah name karena sudah ada dan bisa digunakan sebagai nama restoran
            $table->text('description')->nullable()->after('name');
            $table->string('image_path')->nullable()->after('description');
            $table->string('price_range')->nullable()->after('image_path');
            $table->decimal('rating', 2, 1)->nullable()->after('price_range');
            $table->string('cuisine_type')->nullable()->after('rating');
            $table->string('operating_hours')->nullable()->after('cuisine_type');
        });
    }

    public function down()
    {
        Schema::table('markers', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'image_path',
                'price_range',
                'rating',
                'cuisine_type',
                'operating_hours'
            ]);
        });
    }
};
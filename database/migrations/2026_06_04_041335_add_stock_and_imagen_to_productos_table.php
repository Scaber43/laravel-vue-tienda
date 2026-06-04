<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {

            $table->integer('stock')
                ->default(0)
                ->after('precio');

            $table->string('imagen')
                ->nullable()
                ->after('stock');

        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {

            $table->dropColumn([
                'stock',
                'imagen'
            ]);

        });
    }
};
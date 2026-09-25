<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('footer_menus', function (Blueprint $table) {
            $table->string('type')->default('default')->after('is_static')->comment('default, social, payment');
        });
    }

    public function down(): void
    {
        Schema::table('footer_menus', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

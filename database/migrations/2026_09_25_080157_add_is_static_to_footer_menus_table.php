<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('footer_menus', function (Blueprint $table) {
            // 0 = hiển thị là link (<a>), 1 = văn bản tĩnh (<span> không click)
            $table->boolean('is_static')->default(false)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('footer_menus', function (Blueprint $table) {
            $table->dropColumn('is_static');
        });
    }
};

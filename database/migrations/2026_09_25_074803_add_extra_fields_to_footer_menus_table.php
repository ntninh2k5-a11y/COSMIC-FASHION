<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('footer_menus', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('url');       // Bootstrap Icons class e.g. bi-instagram
            $table->text('description')->nullable()->after('icon'); // Mô tả cho cột tiêu đề
            $table->unsignedInteger('sort_order')->default(0)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('footer_menus', function (Blueprint $table) {
            $table->dropColumn(['icon', 'description', 'sort_order']);
        });
    }
};

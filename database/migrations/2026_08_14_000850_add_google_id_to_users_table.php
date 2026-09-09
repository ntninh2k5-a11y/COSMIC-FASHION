<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột google_id vào sau cột password, cho phép giá trị trống (nullable)
            $table->string('google_id')->nullable()->after('password');
        });
    }


    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa cột google_id nếu chạy lệnh rollback
            $table->dropColumn('google_id');
        });
    }
};
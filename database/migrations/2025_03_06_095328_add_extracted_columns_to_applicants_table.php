<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->text('education')->nullable()->after('cv_url');
            $table->text('qualifications')->nullable()->after('education');
            $table->text('projects')->nullable()->after('qualifications');
            $table->text('personal_info')->nullable()->after('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['education', 'qualifications', 'projects', 'personal_info']);
        });
    }
};

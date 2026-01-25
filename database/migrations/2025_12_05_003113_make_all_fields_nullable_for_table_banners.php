<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAllFieldsNullableForTableBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Make all fields nullable by raw SQL (works without DBAL)
        DB::statement("ALTER TABLE banners MODIFY category_id BIGINT(20) UNSIGNED NULL;");
        DB::statement("ALTER TABLE banners MODIFY title VARCHAR(125) NULL;");
        DB::statement("ALTER TABLE banners MODIFY offer_title VARCHAR(125) NULL;");
        DB::statement("ALTER TABLE banners MODIFY sub_title VARCHAR(125) NULL;");
        DB::statement("ALTER TABLE banners MODIFY image VARCHAR(125) NULL;");
        DB::statement("ALTER TABLE banners MODIFY target VARCHAR(125) NULL DEFAULT '_self';");
        DB::statement("ALTER TABLE banners MODIFY type VARCHAR(125) NULL;");
        DB::statement("ALTER TABLE banners MODIFY description VARCHAR(125) NULL;");
        DB::statement("ALTER TABLE banners MODIFY expire_at TIMESTAMP NULL;");
        DB::statement("ALTER TABLE banners MODIFY is_active TINYINT NULL DEFAULT 1;");
        DB::statement("ALTER TABLE banners MODIFY publish_stat TINYINT NULL;");
        DB::statement("ALTER TABLE banners MODIFY total_click BIGINT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
//            Schema::dropIfExists('banners');
        });
    }
}

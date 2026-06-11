<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->string('sbd', 15)->primary();
            $table->decimal('toan', 4, 2)->nullable();
            $table->decimal('ngu_van', 4, 2)->nullable();
            $table->decimal('ngoai_ngu', 4, 2)->nullable();
            $table->decimal('vat_li', 4, 2)->nullable();
            $table->decimal('hoa_hoc', 4, 2)->nullable();
            $table->decimal('sinh_hoc', 4, 2)->nullable();
            $table->decimal('lich_su', 4, 2)->nullable();
            $table->decimal('dia_li', 4, 2)->nullable();
            $table->decimal('gdcd', 4, 2)->nullable();
            $table ->string('ma_ngoai_ngu', 10)->nullable();
            $table->timestamps();

            $table->index(['toan','vat_li','hoa_hoc']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}

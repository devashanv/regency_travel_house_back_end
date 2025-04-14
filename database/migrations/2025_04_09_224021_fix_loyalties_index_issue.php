<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up()
    {
        Schema::table('loyalties', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['customer_id']);

            // Drop unique index after FK is removed
            $table->dropUnique('loyalties_customer_id_unique');

            // Re-add foreign key without unique
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('loyalties', function (Blueprint $table) {
            // Drop FK again
            $table->dropForeign(['customer_id']);

            // Re-add unique and FK (as it was before)
            $table->unique('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }
};


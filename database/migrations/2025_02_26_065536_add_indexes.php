<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->index('venue_id');
            $table->index('status');
            $table->index('start_date');
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->index(['venue_id', 'status']);
            $table->index(['section', 'row', 'number']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index('event_id');
            $table->index('expires_at');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['events_venue_id_index']);
            $table->dropIndex(['events_status_index']);
            $table->dropIndex(['events_start_date_index']);
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->dropIndex(['seats_venue_id_status_index']);
            $table->dropIndex(['seats_section_row_number_index']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['reservations_user_id_status_index']);
            $table->dropIndex(['reservations_event_id_index']);
            $table->dropIndex(['reservations_expires_at_index']);
        });
    }
}
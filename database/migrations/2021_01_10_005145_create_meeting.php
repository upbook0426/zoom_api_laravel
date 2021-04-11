<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMeeting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('company_name');
            $table->string('email');
            $table->longText('content');

            $table->timestamp('start_at', 0)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->longText('hash');
            $table->boolean('is_canceled');

            $table->longText('zoom_meeting_id');
            $table->longText('zoom_join_url');
            $table->longText('zoom_start_url');
            $table->longText('zoom_password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting');
    }
}

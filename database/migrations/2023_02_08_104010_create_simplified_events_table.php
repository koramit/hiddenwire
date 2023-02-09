<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simplified_events', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreignIdFor(\App\Models\User::class);
            $table->foreignIdFor(\App\Models\LineMessage::class);
            $table->foreignIdFor(\App\Models\LineGroup::class)->nullable();
            $table->text('message')->nullable();
            $table->string('type', 16);
            $table->foreignIdFor(\App\Models\Attachment::class)->nullable();
            $table->timestamp('timestamp')->index();
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
        Schema::dropIfExists('simplified_events');
    }
};

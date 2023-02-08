<?php

use App\Models\LineBot;
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
    public function up(): void
    {
        Schema::create('line_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LineBot::class);
            $table->json('payload');
            $table->boolean('processed')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('line_messages');
    }
};

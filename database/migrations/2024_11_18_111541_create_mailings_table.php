<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mailings', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 32);
            $table->text('message');
            $table->string('status', 32);
            $table->timestamps();
            $table->softDeletes();
        });

        User::create([
            'name' => env('FIRST_USER_NAME', ''),
            'email' => env('FIRST_USER_EMAIL', ''),
            'password' => bcrypt(env('FIRST_USER_PASSWORD', '')),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailings');

        User::find(1)->delete();
    }
};

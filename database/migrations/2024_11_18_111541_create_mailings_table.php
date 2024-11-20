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
            'name' => config('my.first_user_name', ''),
            'email' => config('my.first_user_email', ''),
            'password' => bcrypt(config('my.first_user_password', '')),
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

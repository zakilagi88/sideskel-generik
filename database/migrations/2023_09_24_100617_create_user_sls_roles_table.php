<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_wilayah_roles', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class, 'user_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Role::class, 'role_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('wilayah_id')->constrained('wilayah', 'wilayah_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'wilayah_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_wilayah_roles');
    }
};

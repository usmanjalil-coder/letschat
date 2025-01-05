<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('notifications', function (Blueprint $table) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id(); // Primary key
                $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade'); // Foreign key to users table
                $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('set null'); // Foreign key to users table
                $table->string('action'); // Action type (e.g., 'friend_request')
                $table->string('node_type')->nullable(); // Type of node (e.g., 'post', 'comment')
                $table->string('node_url')->nullable(); // URL of the node
                $table->string('notify_id')->nullable(); // Optional notify identifier
                $table->text('message')->nullable(); // Notification message
                $table->string('notifiable_type')->nullable(); // Polymorphic type (e.g., 'App\\Post')
                $table->unsignedBigInteger('notifiable_id')->nullable(); // Polymorphic ID
                $table->timestamp('read_at')->nullable();
                $table->timestamps(); // created_at and updated_at
            });
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

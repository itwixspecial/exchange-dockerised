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
        Schema::create('completed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_request_id')->constrained('exchange_requests')->onDelete('cascade');
            $table->foreignId('applier_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wallet_acceptor_from_id')->constrained('wallets')->onDelete('cascade');
            $table->foreignId('wallet_acceptor_to_id')->constrained('wallets')->onDelete('cascade');
            $table->foreignId('wallet_creator_from_id')->constrained('wallets')->onDelete('cascade');
            $table->foreignId('wallet_creator_to_id')->constrained('wallets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed');
    }
};

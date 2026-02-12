<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sold_items', function (Blueprint $table) {
            $table->id();
            // 誰が買ったか
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // どの商品を買ったか
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            // 支払い方法（Stripeの決済種別などを保存）
            $table->string('payment_method');
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
        Schema::dropIfExists('sold_items');
    }
}

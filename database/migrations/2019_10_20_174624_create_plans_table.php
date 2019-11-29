<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('plans', function (Blueprint $table) {
			$table->bigIncrements('id')->index();
			$table->string('name')->index();
			$table->string('slug')->unique();
			$table->string('gateway_id');
			$table->decimal('price',6,2);
			$table->boolean('status')->default('0');
			$table->boolean('teams_enabled')->default('0');
			$table->integer('team_limit')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('plans');
	}
}

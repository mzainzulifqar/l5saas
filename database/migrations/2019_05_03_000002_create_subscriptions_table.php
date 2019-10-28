<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
        
		Schema::create('subscriptions', function ($table) {
			$table->increments('id');
			$table->unsignedInteger('user_id');
			$table->string('name');
			$table->string('stripe_id')->collation('UTF8');
			$table->string('stripe_plan');
			$table->integer('quantity');
			$table->timestamp('trial_ends_at')->nullable();
			$table->timestamp('ends_at')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {

		Schema::dropIfExists('subscriptions');
	}
}

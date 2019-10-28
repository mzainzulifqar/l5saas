<?php

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder {
	
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$plans = [

			[
				'name' => 'Monthly',
				'slug' => 'monthly',
				'price' => '20',
				'gateway_id' => 'monthly_20',
				'status' => true,
				'teams_enabled' => false,
				'team_limit' => null,
			],

			[
				'name' => 'Yearly',
				'slug' => 'yearly',
				'price' => '80',
				'gateway_id' => 'yearly_80',
				'status' => true,
				'teams_enabled' => false,
				'team_limit' => null,
			],

			[
				'name' => 'Monthly for 10 users',
				'slug' => 'monthly-for-10-users',
				'price' => '100',
				'gateway_id' => 'team_monthly_20',
				'status' => true,
				'teams_enabled' => true,
				'team_limit' => 10,
			],

			[
				'name' => 'Yearly for 20 users',
				'slug' => 'yearly-for-20-users',
				'price' => '200',
				'gateway_id' => 'team_yearly_20',
				'status' => true,
				'teams_enabled' => true,
				'team_limit' => 20,
			],


		];

		Plan::insert($plans);
	}
}

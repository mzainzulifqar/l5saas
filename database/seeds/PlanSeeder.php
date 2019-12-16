<?php

use App\Models\Plan;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder {
	
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {


		$user = User::create([
			'name' => 'Admin',
			'email' => 'admin@gmail.com',
			'password' => bcrypt('secret'),
			'activated' => true,
		]);

		Team::create(['user_id' => $user->id,'name' => 'LaraThunder']);

		$plans = [

			[
				'name' => 'Monthly',
				'slug' => 'monthly',
				'price' => '20',
				'gateway_id' => 'monthly_20',
				'status' => 1,
				'teams_enabled' => 0,
				'team_limit' => 0,
			],

			[
				'name' => 'Yearly',
				'slug' => 'yearly',
				'price' => '80',
				'gateway_id' => 'yearly_80',
				'status' => 1,
				'teams_enabled' => 0,
				'team_limit' => 0,
			],

			[
				'name' => 'Monthly for 10 users',
				'slug' => 'monthly-for-10-users',
				'price' => '100',
				'gateway_id' => 'team_monthly_20',
				'status' => 1,
				'teams_enabled' => 1,
				'team_limit' => 10,
			],

			[
				'name' => 'Yearly for 20 users',
				'slug' => 'yearly-for-20-users',
				'price' => '200',
				'gateway_id' => 'team_yearly_20',
				'status' => 1,
				'teams_enabled' => 1,
				'team_limit' => 20,
			],


		];

		Plan::insert($plans);
	}
}

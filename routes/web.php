<?php

use Illuminate\Support\Facades\Crypt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/testing',function(){

	$string = 1;
	echo $string."<br>";
	$eny = Crypt::encrypt($string);
	echo $eny."<br>";
	echo "decrypted = ".decrypt($eny);

	// dd(auth()->user()->hasSubscribed());

});

Route::get('/test2/{id}',function($Id){
	
	$id = decrypt($Id);
	$user = App\Models\User::findOrFail($id);
	dd($user->name);
});


Auth::routes();

Route::get('/', 'HomeController@index')->name('welcome');
Route::get('/dashboard', 'DashboardController@index')->name('home');

// Account end here
Route::group(['prefix' => 'account', 'middleware' => ['auth'], 'as' => 'account.', 'namespace' => 'Account'], function () {

	Route::get('/', 'AccountController@index')->name('index');

	// Profile Routes
	Route::get('/profile', 'ProfileController@index')->name('profile');
	Route::post('/profile/update', 'ProfileController@update')->name('profile.update');
	// password route
	Route::get('/password', 'ProfileController@password')->name('password');
	Route::post('/password/update', 'ProfileController@password_update')->name('password.update');
	// Profile Routes end here
});


// account avtivation route
Route::group(['middleware' => 'guest','namespace' => 'Auth\Activation'],function(){

	Route::get('/account/{token}/activate','ActivationController@activateAccount')->name('acivate.account');
	Route::get('/account/resend/activate/link','ActivationController@index')->name('resend.acivate.link');
	Route::post('/account/resend/activate/link','ActivationController@store')->name('resend.acivate.link');
	
});

// Plans  route
Route::group(['prefix' => 'plans','middleware' => 'subscription.inactive'],function(){

	Route::get('/','PlansController@index')->name('plans.index');
	Route::get('/team_plans','PlansController@team_plans')->name('plans.team');
});


// Teams  route
Route::group(['prefix' => 'teams','namespace' => 'Account','middleware' => ['auth','subscription.DoesNotHaveTeamPlan']],function(){

	Route::get('/','TeamSubscriptionController@index')->name('teams.index');
	Route::post('/team_name','TeamSubscriptionController@update')->name('teams.update');
	Route::post('/add/members/{team_id}', 'TeamSubscriptionController@addMembers')->name('teams.addMembers');
	Route::get('/remove/members/{user_id}', 'TeamSubscriptionController@removeMember')->name('teams.removeMembers');
});

// Subscription  route
Route::group(['prefix' => 'subscription','middleware' => ['auth'],'as' => 'subscription.'],function(){

		// checkout route
		Route::group(['middleware' => ['subscription.inactive']],function(){
			Route::get('/','SubscriptionController@index')->name('index');
			Route::post('/','SubscriptionController@store')->name('store');
		});
	

		Route::group(['middleware' => ['subscription.cancelled','IsOnPiggyBackSubscription']],function(){
			
			Route::get('/resume','SubscriptionController@resume_subscription')->name('resume');
			Route::post('/resume','SubscriptionController@resume')->name('cancel.resume');
			
		});

		Route::group(['middleware' => ['subscription.notcancelled','IsOnPiggyBackSubscription']],function(){
			 
			 /*Change Route */
			Route::get('/change','SubscriptionController@change_subscription')->name('change');

			/*Cancel Route */
			Route::get('/cancel','SubscriptionController@cancel_subscription')->name('cancel');
			Route::post('/cancel','SubscriptionController@cancel')->name('cancel.plan');
			
		});

		Route::get('update_card', 'SubscriptionController@update_card')->name('update')->middleware('subscription.customer','IsOnPiggyBackSubscription');
		Route::post('update_card', 'SubscriptionController@update')->name('update_card')->middleware('subscription.customer','IsOnPiggyBackSubscription');
});

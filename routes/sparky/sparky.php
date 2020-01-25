<?php 


Route::get('/', 'HomeController@index')->name('welcome');
Route::get('/dashboard', 'DashboardController@index')->name('home');

Route::group(['prefix' => 'account', 'middleware' => 'guest', 'namespace' => 'Account'], function ()
{
	// twoFactor AuthenticationLogin
	Route::get('/twoFactor/login', 'TwoFactorAuthenticationLogin@login');
	Route::post('/twoFactor/login', 'TwoFactorAuthenticationLogin@loginWithCode')->name('twoFactor.login');
	// end of twoFactor AuthenticationLogin
});
// Account end here
Route::group(['prefix' => 'account', 'middleware' => ['auth'], 'as' => 'account.', 'namespace' => 'Account'], function ()
{

	Route::get('/', 'AccountController@index')->name('index');

	// Profile Routes
	Route::get('/profile', 'ProfileController@index')->name('profile');
	Route::post('/profile/update', 'ProfileController@update')->name('profile.update');
	// password route
	Route::get('/password', 'ProfileController@password')->name('password');
	Route::post('/password/update', 'ProfileController@password_update')->name('password.update');
	// Profile Routes end here

	// account deactivation
	Route::get('deactivate', 'DeactivationController@index')->name('deactive.index');
	Route::post('deactivate', 'DeactivationController@deactivate')->name('deactivate.yes');
	// account deactivation end here

	// twoFactor Authentication
	Route::get('twoFactor', 'TwoFactorAuthenticationController@index')->name('twoFactor.index');
	Route::post('twoFactor', 'TwoFactorAuthenticationController@store')->name('twoFactor.store');
	Route::post('twoFactor/verify', 'TwoFactorAuthenticationController@codeVerify')->name('twoFactor.verify');
	Route::post('twoFactor/cancel', 'TwoFactorAuthenticationController@destroy')->name('twoFactor.destroy');
	// end of twoFactor

});

// account avtivation route
Route::group(['middleware' => 'guest', 'namespace' => 'Auth\Activation'], function ()
{

	Route::get('/account/{token}/activate', 'ActivationController@activateAccount')->name('acivate.account');
	Route::get('/account/resend/activate/link', 'ActivationController@index')->name('resend.acivate.link');
	Route::post('/account/resend/activate/link', 'ActivationController@store')->name('resend.acivate.link');

});

// Plans  route
Route::group(['prefix' => 'plans', 'middleware' => 'subscription.inactive'], function ()
{

	Route::get('/', 'PlansController@index')->name('plans.index');
	Route::get('/team_plans', 'PlansController@team_plans')->name('plans.team');
});

// Teams  route
Route::group(['prefix' => 'teams', 'namespace' => 'Account', 'middleware' => ['auth', 'subscription.DoesNotHaveTeamPlan']], function ()
{

	Route::get('/', 'TeamSubscriptionController@index')->name('teams.index');
	Route::post('/team_name', 'TeamSubscriptionController@update')->name('teams.update');
	Route::post('/add/members/{team_id}', 'TeamSubscriptionController@addMembers')->name('teams.addMembers');
	Route::get('/remove/members/{user_id}', 'TeamSubscriptionController@removeMember')->name('teams.removeMembers');
	Route::post('/invite/members','TeamSubscriptionController@inviteMemeber')->name('invite.team.member');
	
});
Route::get('accept/{token}/invite','Account\TeamSubscriptionController@acceptinvite')->name('accept.invite.team.member');

// Subscription  route
Route::group(['prefix' => 'subscription', 'middleware' => ['auth'], 'as' => 'subscription.'], function ()
{

	// checkout route
	Route::group(['middleware' => ['subscription.inactive']], function ()
	{
		Route::get('/', 'SubscriptionController@index')->name('index');
		Route::post('/', 'SubscriptionController@store')->name('store');
	});

	Route::group(['middleware' => ['subscription.cancelled', 'IsOnPiggyBackSubscription']], function ()
	{

		Route::get('/resume', 'SubscriptionController@resume_subscription')->name('resume');
		Route::post('/resume', 'SubscriptionController@resume')->name('cancel.resume');

	});

	Route::group(['middleware' => ['subscription.notcancelled', 'IsOnPiggyBackSubscription']], function ()
	{

		/*Change Route */
		Route::get('/change', 'SubscriptionController@change_subscription')->name('change');
		Route::post('/change', 'SubscriptionController@changeSubscriptionPlan')->name('change.plan.store');

		/*Cancel Route */
		Route::get('/cancel', 'SubscriptionController@cancel_subscription')->name('cancel');
		Route::post('/cancel', 'SubscriptionController@cancel')->name('cancel.plan');

	});

	Route::get('update_card', 'SubscriptionController@update_card')->name('update')->middleware('subscription.customer', 'IsOnPiggyBackSubscription');
	Route::post('update_card', 'SubscriptionController@update')->name('update_card')->middleware('subscription.customer', 'IsOnPiggyBackSubscription');
});

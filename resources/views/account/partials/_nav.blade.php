<div class="card" style="margin-bottom: 15px;">
	<div class="card-header bg-grey"> <i class="icon-cog"></i> Settings</div>
		
		<a href="{{route('account.index')}}" class="list-group-item list-group-item-action" style="{{request()->url() == route('account.index') ? 'border-left:3px solid #e66761;' : ''}}"><i class="icon-edit"></i> &nbsp;&nbsp;Account</a>
		
		<a  href="{{route('account.profile')}}" class="list-group-item list-group-item-action" style="{{request()->url() == route('account.profile') ? 'border-left: 3px solid #e66761;' : ''}}">
		 <i class="icon-pencil"></i> &nbsp;&nbsp;Profile</a>
		 
		<a href="{{route('account.password')}}" class="list-group-item list-group-item-action"
		style="{{request()->url() == route('account.password') ? 'border-left: 3px solid #e66761;' : ''}}"> <i class="icon-lock"></i> &nbsp;&nbsp;Change Password</a>

</div>

<div class="card" style="margin-bottom: 15px;">
	<div class="card-header bg-grey"><i class="icon-credit-card"></i> &nbsp;&nbsp;Billing</div>
		<div class="list-group">
			@subscribed
			
			 	@notCancelledSubscription
					<a href="{{ route('subscription.change') }}" class="list-group-item list-group-item-action">Change Plan</a>

					<a href="{{ route('subscription.cancel') }}" class="list-group-item list-group-item-action">Cancel Plan</a>
				@endnotCancelledSubscription

			@endsubscribed

				@cancelledSubscription
				  <a href="{{ route('subscription.resume') }}" class="list-group-item list-group-item-action">Resume Plan</a>
				@endcancelledSubscription

				@isCustomer()
				 <a href="{{ route('subscription.update') }}" class="list-group-item list-group-item-action disabled">Card Update</a>
				 @endisCustomer()


				@isTeamPlan()
				 <a href="{{ route('teams.index') }}" class="list-group-item list-group-item-action disabled">Manage Team</a>
				@endisTeamPlan()
			</div>
</div>



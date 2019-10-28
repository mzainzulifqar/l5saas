@extends('account.layouts.default')

@section('account.content')

	<div class="card">
        <div class="card-header bg-secondary" style="color:white;">Cancel Subscription</div>
		 <div class="card-body">
		 	<form action="{{ route('subscription.cancel.plan') }}" method="POST" role="form">
		 		@csrf

		 		<div class="form-group">
					<h3>Confirm your Cancellation..</h3>
				</div>

		 		<button type="submit" class="btn btn-danger">Confirm</button>
		 	</form>
         </div>
    </div>


@endsection()


@extends('account.layouts.default')

@section('account.content')

	<div class="card">
        <div class="card-header bg-dark" style="color:white;">Resume Subscription</div>
		 <div class="card-body">
		 	<form action="{{ route('subscription.cancel.resume') }}" method="POST" role="form">
		 		@csrf

		 		<div class="form-group">
					<h3>Resume your Subscription..</h3>
				</div>

		 		<button type="submit" class="btn btn-danger">Confirm</button>
		 	</form>
         </div>
    </div>


@endsection()
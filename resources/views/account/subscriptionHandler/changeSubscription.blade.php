@extends('account.layouts.default')

@section('account.content')
	
	

	<div class="card">
        <div class="card-header bg-dark" style="color:white;">Change Plan</div>
		 <div class="card-body">
		 	<div class="alert alert-danger">
		 		<strong>Current Plan</strong> {{ auth()->user()->plan[0]->name }} 
		 		<br><strong>Price</strong> ($ {{ auth()->user()->plan[0]->price }}) 
			</div>
		 	

		 	<form action="{{ route('subscription.change.plan.store') }}" method="POST" role="form">
		 		@csrf
		 		<div class="form-group">
		 			<label for="">Change Plan</label>
		 			
		 			<select name="plan" class="form-control @error('plan') is-invalid @enderror" required="required">
		 				@forelse($plans as $plan)
		 				<option value="{{$plan->gateway_id}}">{{$plan->name}}</option>
		 				@empty
		 				@endforelse
		 			</select>
		 			@error('plan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

		 		</div>
		 	
		 		<button type="submit" class="btn btn-outline-danger">Submit</button>
		 	</form>
         </div>
    </div>


@endsection()
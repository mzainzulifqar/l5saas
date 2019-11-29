@extends('account.layouts.default')

@section('account.content')

	<div class="card">
        <div class="card-header bg-dark" style="color:white;">Change Plan</div>
		 <div class="card-body">
		 	<form action="" method="POST" role="form">
		 	
		 		<div class="form-group">
		 			<label for="">Change Plan</label>
		 			
		 			<select name="plans" id="input" class="form-control" required="required">
		 				@forelse($plans as $plan)
		 				<option value="{{$plan->gateway_id}}">{{$plan->name}}</option>
		 				@empty
		 				@endforelse
		 			</select>


		 		</div>
		 	
		 		<button type="submit" class="btn btn-outline-primary">Submit</button>
		 	</form>
         </div>
    </div>


@endsection()
@extends('layouts.app')

@section('content')
	<div class="container">

		<h3 class="text-center" style="padding-bottom: 20px;">Plans</h3>
		
		<div class="row">
        
		
       
        @forelse($plans as $plan)
        <div class="col-md-4 col-xs-12 col-sm-12">
        <div class="card card-pricing text-center px-3 mb-4">
            <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white shadow-sm">{{$plan->name}}</span>
            <div class="bg-transparent card-header pt-4 border-0">
                <h1 class="h1 font-weight-normal text-primary text-center mb-0" data-pricing-value="15">$<span class="price">{{number_format($plan->price)}}</span><span class="h6 text-muted ml-2">/ per {{strtolower($plan->name)}}</span></h1>
            </div>
            <div class="card-body pt-0">
                <ul class="list-unstyled mb-4">
                    <li>Access to Everything</li>
                    <li>Monthly updates</li>
                    <li>Free cancelation</li>
                </ul>
                <a href="{{route('subscription.store',['plan' => $plan->slug])}}" class="btn btn-outline-secondary mb-3">Order now</a>
            </div>
        </div>
        </div>
       @empty
       @endforelse



       @if (request()->url() != route('plans.team'))
        <div class="col-md-4 col-xs-12 col-sm-12">
        <div class="card card-pricing popular shadow text-center px-3 mb-4">
            <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white shadow-sm">Team plans</span>
            <div class="bg-transparent card-header pt-4 border-0">
            	
            	<h1 class="h1 font-weight-normal text-primary text-center mb-0" data-pricing-value="30"> Starting</h1>

            </div>
            <div class="card-body pt-0">
                <ul class="list-unstyled mb-4">
                    <li>$ 20 Per Month</li>
                   
                </ul>
                <a href="{{ route('plans.team') }}" class="btn btn-primary mb-3">View Now</a>
            </div>
        </div>
    </div>
      @endif

    </div>

		
	</div>

@endsection()
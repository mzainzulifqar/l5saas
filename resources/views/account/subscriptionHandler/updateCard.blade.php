@extends('account.layouts.default')

@section('account.content')

	<div class="card">
	
        <div class="card-header" style="color:black;background-color: #EDEAE4;">Update Card</div>
          <div class="card-body">
            <form action="{{ route('subscription.update_card') }}" method="POST" role="form" id="payment-form">
              @csrf
              <div class="form-group">
                <label for="card-element">
                  Credit or debit card
                </label>
                <div id="card-element">
                  <!-- A Stripe Element will be inserted here. -->
                </div>
                <!-- Used to display form errors. -->
                <div id="card-errors" class="text-danger" role="alert"></div>
              </div>
              <button type="submit" id="submit_btn" class="btn btn-outline-danger">Update</button>
            </form>
        </div>
  </div>


@endsection()
@push('js')
  <script src="{{ asset('stripe/stripe.js') }}" defer></script>
@endpush
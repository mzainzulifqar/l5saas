@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Subscription</div>
                <div class="card-body">

                  @if (session()->has('error'))
                    
                  <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Whoops!</strong> {{session()->get('error')}}
                  </div>
                  
                  @endif

                     <form action="{{ route('subscription.store') }}" method="POST" role="form" id="payment-form">
                    @csrf


                <div class="form-group">
                    <label for="">Plans</label>

                    <select name="plans" id="input" class="form-control @error('plans') is-invalid @enderror" required="required">

                         @forelse($plans as $plan)
                         <option value="{{$plan->gateway_id}}" {{request()->has('plan') && request('plan') == $plan->slug ? 'selected' : ''}}>{{$plan->name}} ($ {{$plan->price}})</option>
                         @empty
                         @endforelse
                         
                    </select>

                    @error('plans')
                        <span class="help-block text-danger">
                            {{$message}}
                        </span>
                     @enderror

                </div>

                    <div class="form-group">
                        <label for="email">Coupon</label>
                        <input type="text" class="form-control @error('coupon') is-invalid @enderror" name="coupon" id="" placeholder="Coupon" value={{old('coupon')}}>

                          @error('coupon')
                        <span class="help-block text-danger">
                            {{$message}}
                        </span>
                        @enderror

                    </div>

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


                    <button type="submit" id="submit_btn" class="btn btn-outline-danger">Subscribe</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
  <script src="{{ asset('stripe/stripe.js') }}" defer></script>
@endpush
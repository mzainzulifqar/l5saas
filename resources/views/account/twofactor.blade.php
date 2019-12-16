@extends('account.layouts.default')
@push('name')
    <!-- Latest compiled and minified CSS -->
        
@endpush
@section('account.content')

	<div class="card">
        <div class="card-header bg-grey">Two Factor Authentication</div>
    		 <div class="card-body">

            @if (auth()->user()->twoFactorVerificationPending())
                <form action="{{ route('account.twoFactor.verify') }}" method="POST" role="form">
                    @csrf

                    <div class="form-group">
                        <label for="">Verification Code</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" id="" placeholder="Code" value={{old('code')}}>

                    @error('code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                    <button type="submit" class="btn btn-success">Verify</button>
                </form>

                <hr>

                 <form action="{{ route('account.twoFactor.destroy') }}" method="POST" role="form">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cancel Verification</button>
                </form>


            @elseif(auth()->user()->isTwoFactorEnabled())
               
                <form action="{{ route('account.twoFactor.destroy') }}" method="POST" role="form">
                    @csrf
                    <button type="submit" class="btn btn-danger">De-active Two Factor</button>
                </form>
                
            @else

                <form action="{{ route('account.twoFactor.store') }}" method="POST" role="form">
                    @csrf

                 <div class="form-group">
                    
                        <label for="">Country</label>
                        
                        <select name="country"  class="form-control @error('country') is-invalid @enderror" required="required">
                            
                            @forelse($counteries as $country)

                            <option value="{{ $country->dialing_code }}">{{ $country->name }} (+{{ $country->dialing_code }})</option>
                            @empty
                            @endforelse
                        </select>
                    @error('country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>


                <div class="form-group">
                        <label for="">Phone Number</label>
                        <input type="text" name="number" class="form-control @error('number') is-invalid @enderror" id="" placeholder="number" value={{old('number')}}>

                    @error('number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                    <button type="submit" class="btn btn-danger">Enable</button>
                </form>
            @endif

             </div>
    </div>

@endsection()
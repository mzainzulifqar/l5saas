@extends('account.layouts.default')

@section('account.content')

	<div class="card">
        <div class="card-header bg-grey">Deactivate Account</div>
    		 <div class="card-body">
                <form action="{{ route('account.deactivate.yes') }}" method="POST" role="form">
                    @csrf


                <div class="form-group">
                        <label for="">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" id="" placeholder="Current Password" value={{old('current_password')}}>

                       @error('current_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                    <button type="submit" class="btn btn-danger">Deactivate</button>
                </form>
             </div>
    </div>

@endsection()
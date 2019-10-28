@extends('account.layouts.default')

@section('account.content')

	<div class="card">
        <div class="card-header bg-grey">Password Update</div>
    		 <div class="card-body">
                <form action="{{ route('account.password.update') }}" method="POST" role="form">
                    @csrf


                <div class="form-group">
                        <label for="">Current Password</label>
                        <input type="password" name="current_password" class="form-control" id="" placeholder="Current Password" value={{old('current_password')}}>

                        @error('current_password')
                        <span class="help-block text-danger">
                            {{$message}}
                        </span>
                        @enderror

                </div>

                <div class="form-group">
                        <label for="">New Password</label>
                        <input type="password" name="password" class="form-control" id="" placeholder="Name" value={{old('password')}}>

                        @error('password')
                        <span class="help-block text-danger">
                            {{$message}}
                        </span>
                        @enderror

                </div>

                <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="" placeholder="Confirm Password" value={{old('name')}}>

                        @error('password_confirmation')
                        <span class="help-block text-danger">
                            {{$message}}
                        </span>
                        @enderror

                </div>



                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
             </div>
    </div>

@endsection()
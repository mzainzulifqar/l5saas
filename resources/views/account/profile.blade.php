@extends('account.layouts.default')

@section('account.content')

	<div class="card">
        <div class="card-header bg-grey">Profile Update</div>
    		 <div class="card-body">
                <form action="{{ route('account.profile.update') }}" method="POST" role="form">
                    @csrf


                <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control" id="" placeholder="Name" value="{{old('name',Auth::user()->name)}}">

                        @error('name')
                        <span class="help-block text-danger">
                            {{$message}}
                        </span>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="form-control" name="email" id="" placeholder="Email" value={{old('email',Auth::user()->email)}}>

                          @error('email')
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
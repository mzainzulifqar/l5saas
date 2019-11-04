@extends('account.layouts.default')

@section('account.content')

	<div class="card">
        <div class="card-header bg-grey">Manage Team</div>
    		 <div class="card-body">
                <form action="{{ route('teams.update') }}" method="POST" role="form">
                    @csrf


                <div class="form-group">
                        <label for="">Team Name</label>
                        <input type="text" name="name" class="form-control" id="" placeholder="Name" value="{{old('name',$team->name)}}">

                        @error('name')
                        <span class="help-block text-danger">
                            {{$message}}
                        </span>
                        @enderror

                </div>
                    <button type="submit" class="btn btn-outline-danger">Update</button>
                </form>
             </div>
    </div>

@endsection()
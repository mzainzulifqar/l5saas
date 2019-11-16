@extends('account.layouts.default')

@section('account.content')

	<div class="card" style="margin-bottom: 10px;">
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


    <div class="card">
        <div class="card-header bg-grey">Team Members</div>
             <div class="card-body">

                <div style="padding-bottom: 10px;">
                     <form action="{{ route('teams.addMembers',$team->id) }}" method="POST" role="form">
                        @csrf


                    <div class="form-group">
                            <label for="">Add Members</label>
                            <input type="email" name="member_email" class="form-control @error('member_email') is-invalid @enderror" id="" placeholder="Email" value="{{old('email')}}">

                            @error('member_email')
                            <span class="help-block text-danger">
                                {{$message}}
                            </span>
                            @enderror

                    </div>
                        <button type="submit" class="btn btn-outline-success">Add Member</button>
                    </form>
                </div>

                {{-- displaying team members --}}
                @if ($team->users->count())
                        <p class="pull-right">Team Members &nbsp;{{$team->users->count() .' / '. auth()->user()->PlanTeamLimit()}}</p>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Added</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($team->users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->pivot->created_at->toDateString()}}</td>

                                <td><a href="{{ route('teams.removeMembers',$user->id) }}"  class="btn btn-danger">Delete</a></td>
                            </tr>
                            @empty
                               
                            @endforelse 
                            
                        </tbody>
                    </table>
                @else
                   <h2 class="text-center">You've added any members yet!</h2>
                @endif
               
             </div>
    </div>

@endsection()
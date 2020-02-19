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

                @if ($errors->any())
                <div class="alert alert-danger">
                     <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if ($team->users->count() < auth()->user()->PlanTeamLimit())
                
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
                        <a  href="javascript:" data-toggle="modal" data-target="#myModal" class="btn btn-outline-danger">Invite Member</a>
                    </form>



                    {{-- starting modal code from here --}}

                    <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Invite Memeber</h4>
                      </div>
                      <div class="modal-body">
                        <form action="{{ route('invite.team.member') }}" method="POST" role="form">
                            @csrf
                            <div class="form-group">
                                <label for="">Email:</label>
                                <input type="text" class="form-control" name="email" id="" placeholder="Email">
                                <span class="help-block">His Random Password will be (123456)</span>
                            </div>
                        
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>

                  </div>
                </div>

                {{--  --}}
                </div>
                @endif()
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

                                <td><a href="{{ route('teams.removeMembers',$user->id) }}"  class="btn btn-danger">Remove</a></td>
                            </tr>
                            @empty
                               
                            @endforelse 
                            
                        </tbody>
                    </table>
                @else
                   <h2 class="text-center">You haven't added any members yet!</h2>
                @endif
               
             </div>
    </div>

@endsection()
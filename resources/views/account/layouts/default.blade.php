@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">

			<div class="col-md-3">

				@include('account.partials._nav')

			</div>

			<div class="col-md-9">

				@include('account.partials.alert')

				@yield('account.content')

			</div>

		</div>
	</div>

@endsection()
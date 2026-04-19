@extends('admin.layout')
@section('content')
<form method="POST" action="{{ route('admin.universities.store') }}">
@csrf
<input name="name" placeholder="Name">
<button>Save</button>
</form>
@endsection

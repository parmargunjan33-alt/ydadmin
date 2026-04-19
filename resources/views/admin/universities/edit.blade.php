@extends('admin.layout')
@section('content')
<form method="POST" action="{{ route('admin.universities.update',$row->id) }}">
@csrf @method('PUT')
<input name="name" value="{{ $row->name }}">
<button>Update</button>
</form>
@endsection

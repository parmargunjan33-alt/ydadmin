@extends('admin.layout')
@section('content')
<a href="{{ route('subjects.create') }}" class="btn btn-primary mb-2">Add</a>
<table class="table">
<tr><th>ID</th><th>Name</th><th>Action</th></tr>
@foreach($data as $row)
<tr>
<td>{{ $row->id }}</td>
<td>{{ $row->name ?? '' }}</td>
<td>
<a href="{{ route('subjects.edit',$row->id) }}">Edit</a>
<form method="POST" action="{{ route('subjects.destroy',$row->id) }}">
@csrf @method('DELETE')
<button>Delete</button>
</form>
</td>
</tr>
@endforeach
</table>
@endsection

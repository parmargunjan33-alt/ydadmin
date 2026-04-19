@extends('admin.layout')
@section('content')
<form method="POST" action="{{ route('subjects.update',$row->id) }}" enctype="multipart/form-data">
@csrf @method('PUT')

<select name="university_id" value="{{ $row->name ??  }}">
@foreach($universities as $u)
<option value="{{ $u->id }}">{{ $u->name }}</option>
@endforeach
</select>
<select name="course_id">
@foreach($courses ?? [] as $c)
<option value="{{ $c->id }}">{{ $c->name }}</option>
@endforeach
</select>
<select name="semester_id">
@foreach($semesters ?? [] as $s)
<option value="{{ $s->id }}">{{ $s->name }}</option>
@endforeach
</select>
<input name="name" placeholder="Subject Name">
<textarea name="summary"></textarea>
<input type="file" name="files[]" multiple>

<button>Update</button>
</form>
@endsection

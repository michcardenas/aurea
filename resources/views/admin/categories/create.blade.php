@extends('layouts.admin')

@section('title', 'Nueva categoría')
@section('page_title', 'Nueva categoría')

@section('content')
<form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
    @include('admin.categories._form')
</form>
@endsection

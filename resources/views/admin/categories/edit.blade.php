@extends('layouts.admin')

@section('title', 'Editar categoría')
@section('page_title', 'Editar categoría')

@section('content')
<form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.categories._form')
</form>
@endsection

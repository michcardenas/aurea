@extends('layouts.admin')
@section('title', 'Editar marca')
@section('page_title', 'Editar marca')

@section('content')
<form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.brands._form')
</form>
@endsection

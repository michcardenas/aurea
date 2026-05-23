@extends('layouts.admin')
@section('title', 'Nueva marca')
@section('page_title', 'Nueva marca')

@section('content')
<form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
    @include('admin.brands._form')
</form>
@endsection

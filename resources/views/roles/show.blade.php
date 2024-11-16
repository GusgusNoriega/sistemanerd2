@extends('layouts.app2')

@section('title', 'Home')

@section('header')
    @include('components.header') <!-- Reemplaza con el componente de header -->
@endsection

@section('sidebar')
    @include('components.sidebar') <!-- Reemplaza con el componente de sidebar -->
@endsection

@section('content')
<h1>Permisos del Rol: {{ $role->name }}</h1>

@if(session('success'))
    <div>
        {{ session('success') }}
    </div>
@endif

@endsection

@section('footer')
    @include('components.footer') <!-- Reemplaza con el componente de footer -->
@endsection

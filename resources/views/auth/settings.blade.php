@extends('layouts.app2')

@section('title', 'Home')

@section('header')
    @include('components.header') <!-- Reemplaza con el componente de header -->
@endsection

@section('sidebar')
    @include('components.sidebar') <!-- Reemplaza con el componente de sidebar -->
@endsection

@section('content')
    <h2 class="text-2xl font-bold">Welcome to the Home Page</h2>
    <p>This is the main content area.</p>
@endsection

@section('footer')
    @include('components.footer') <!-- Reemplaza con el componente de footer -->
@endsection
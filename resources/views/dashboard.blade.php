@extends('layout')

@php
    $title = 'Dashboard';
@endphp

@section('title', $title)

@section('content')
    <div>
        <section class="content__header">
            <h1 class="content__title">{{$title}}</h1>
        </section>

    </div>
@endsection

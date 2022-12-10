@extends('layout')

@php
    $title = 'Products';
@endphp

@section('title', $title)

@section('content')
    <div class="content">
        <section class="content__header">
            <h1 class="content__title">{{$title}}</h1>
            <div class="">
                <k-button href="{{route('products.create')}}">Add</k-button>
            </div>
        </section>
        <product-list :data-route="'{{route('manager.products.list')}}'"></product-list>
    </div>
@endsection

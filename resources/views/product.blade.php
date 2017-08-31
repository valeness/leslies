@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-10">
                        <img class="primary-image full-width" src="{{ $product['_source']['images'][0] }}">
                    </div>

                    <div class="col-sm-2">
                        @foreach($product['_source']['images'] as $k => $image)
                            <div class="card">
                                <img class="secondary-image full-width" src="{{$image}}">
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="col-sm-6">
                <h1 class="text-center">{{ $product['_source']['name'] }}</h1>
                <h3 class="text-center">{{ $product['_source']['brand'] }}</h3>
                <p>{{ $product['_source']['description'] }}</p>
            </div>

        </div>
    </div>

@stop
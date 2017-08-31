@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <h6>Filters</h6>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="list-group">
                          <a href="#" class="list-group-item">Prices</a>
                          <a href="#" class="list-group-item">Category</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-10">
                @if(empty($products))
                    <div class="row">
                        <h3 class="full-width text-center">We're Sorry! No Products Were Found for That Request!</h3>
                        <h5 class="full-width text-center">Try these suggested products instead</h5>
                    </div>
                @endif
                <div class="row">

                    @if(empty($products) && !empty($suggested_prods))
                        @foreach($suggested_prods as $prod)
                            @include('components.prodbox')
                        @endforeach
                    @endif
                    @foreach($products as $prod)
                        @include('components.prodbox')
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@stop
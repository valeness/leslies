<div class="col-sm-3">
    <a href="/product/{{$prod['_source']['id']}}">
        <div class="card">
            <img class="card-img-top" src="{{ $prod['_source']['images'][0] }}" alt="Card image cap">
            <div class="card-block">
                <p class="card-text">{{ $prod['_source']['name'] }}</p>
            </div>
        </div>
    </a>
</div>
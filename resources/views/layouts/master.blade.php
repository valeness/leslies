<!DOCTYPE html>
<HTML>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <style>
        .card {
            border:none;
            margin-bottom: 20px;
        }

        .full-width {
            width: 100%;
        }

        .secondary-image {
            cursor: pointer;
        }


    </style>

</head>

<body>

<nav class="navbar navbar-light bg-faded">

    <a class="navbar-brand" href="/">LESLIES</a>

    <div class="form-inline">
        <input @if(!empty($query)) value="{{$query}}" @endif id="product_query" class="form-control mr-sm-2" type="text" placeholder="Search">
        <button type="button" class="submit-search btn btn-outline-primary">Search</button>
    </div>
</nav>

@section('content') @show

@section('scripts')
<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<script>
    $('.secondary-image').on('click', function() {
        var src = $(this).attr('src');
        $('.primary-image').attr('src', src);
    });

    $('#product_query').on('keypress', function(e) {
        if(e.which == 13) {
            search();
        }
    });

    $('.submit-search').on('click', function() {
        search();
    });

    function search() {
        var query = $('#product_query').val();
        window.location.href = '/search?query=' + query;
    }

</script>
@show

</body>

</HTML>
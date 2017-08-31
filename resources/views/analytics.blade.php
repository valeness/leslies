@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div id="container"></div>
            </div>
        </div>
    </div>

    @section('scripts')
        @parent
        <script src="//code.highcharts.com/stock/highstock.js"></script>
        <script>
            $.ajax({
                url : '/api/analytics',
                data : {},
                success : function(r) {
                    if(r.data.length > 0) {
                        var start = +new Date();

                        Highcharts.stockChart('container', {
                            chart: {
                                events: {
                                    load: function () {
                                        this.setTitle(null, {
                                            text: 'Built chart in ' + (new Date() - start) + 'ms'
                                        });
                                    }
                                },
                                zoomType: 'x'
                            },

                            rangeSelector: {

                                buttons: [{
                                    type: 'day',
                                    count: 3,
                                    text: '3d'
                                }, {
                                    type: 'week',
                                    count: 1,
                                    text: '1w'
                                }, {
                                    type: 'month',
                                    count: 1,
                                    text: '1m'
                                }, {
                                    type: 'month',
                                    count: 6,
                                    text: '6m'
                                }, {
                                    type: 'year',
                                    count: 1,
                                    text: '1y'
                                }, {
                                    type: 'all',
                                    text: 'All'
                                }],
                                selected: 3
                            },

                            yAxis: {
                                title: {
                                    text: '# of Hits'
                                }
                            },

                            title: {
                                text: 'Number of Pageviews by PageType'
                            },

                            subtitle: {
                                text: 'Built chart in ...' // dummy text to reserve space for dynamic subtitle
                            },

                            series: r.data

                        });
                    } else {
                        $('#container').html('<h4 class="text-center">No Data Points Found! Click around the site a bit. :D</h4');
                    }
                }
            });
        </script>
    @stop

@stop
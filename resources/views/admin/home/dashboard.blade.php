@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.dashboard') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
    @parent
    <style>
    #canvas-holder {
        width: 100%;
        margin-top: 50px;
        text-align: center;
    }
    #chartjs-tooltip {
        opacity: 1;
        position: absolute;
        background: rgba(0, 0, 0, .7);
        color: white;
        padding: 3px;
        border-radius: 3px;
        -webkit-transition: all .1s ease;
        transition: all .1s ease;
        pointer-events: none;
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
    }
    #chartjs-tooltip.below {
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
    }
    #chartjs-tooltip.below:before {
        border: solid;
        border-color: #111 transparent;
        border-color: rgba(0, 0, 0, .8) transparent;
        border-width: 0 8px 8px 8px;
        bottom: 1em;
        content: "";
        display: block;
        left: 50%;
        position: absolute;
        z-index: 99;
        -webkit-transform: translate(-50%, -100%);
        transform: translate(-50%, -100%);
    }
    #chartjs-tooltip.above {
        -webkit-transform: translate(-50%, -100%);
        transform: translate(-50%, -100%);
    }
    #chartjs-tooltip.above:before {
        border: solid;
        border-color: #111 transparent;
        border-color: rgba(0, 0, 0, .8) transparent;
        border-width: 8px 8px 0 8px;
        bottom: 1em;
        content: "";
        display: block;
        left: 50%;
        top: 100%;
        position: absolute;
        z-index: 99;
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
    }
    </style>
@endsection
 
@section('content')

<div class="uk-container uk-container-center uk-margin-top">
    <div class="uk-panel">
        <h1 class="uk-float-left">{{ trans('admin.dashboard') }}</h1>
        <a href="{{ url('/admin/listings/create') }}" class="uk-button uk-button-primary uk-button-large uk-float-right">{{ trans('admin.publish_property') }}</a>
    </div>
    
    <div class="uk-grid uk-grid-match" data-uk-grid-match="{target:'.uk-panel'}">
        @if(Auth::user()->confirmed)
            <!-- First row -->
            <div class="uk-width-small-1-1 uk-width-medium-1-4 uk-width-large-1-4 uk-margin-top">
                <div class="uk-panel uk-panel-box uk-panel-box-primary">
                    <a href="{{ url('/admin/listings') }}" style="text-decoration:none">
                        <h3 class="uk-panel-title">{{ trans('admin.listings') }}</h3>
                    </a>
                    <a href="{{ url('/admin/listings') }}" style="text-decoration:none">
                        <h1 class="uk-text-center">{{ $listingCount }} <i class="uk-icon-home"></i></h1>
                    </a>
                </div>
            </div>
            <div class="uk-width-small-1-1 uk-width-medium-1-4 uk-width-large-1-4 uk-margin-top">
                <div class="uk-panel uk-panel-box uk-panel-box-primary">
                    <a href="{{ url('/admin/messages') }}" style="text-decoration:none"><h3 class="uk-panel-title">{{ trans('admin.unanswered_messages') }}</h3></a>

                    @if($notAnsweredMessages == 0)
                        <a href="{{ url('/admin/messages') }}" style="text-decoration:none">
                            <h1 class="uk-text-center uk-text-success">{{ $notAnsweredMessages }} <i class="uk-icon-check"></i></h1>
                        </a>
                    @else
                        <a href="{{ url('/admin/messages') }}" style="text-decoration:none">
                            <h1 class="uk-text-center uk-text-warning">{{ $notAnsweredMessages }} <i class="uk-icon-envelope-o"></i></h1>
                        </a>
                    @endif
                </div>
            </div>
            <div class="uk-width-1-4 uk-hidden-small uk-margin-top">
                <div class="uk-panel uk-panel-box uk-panel-box-primary">
                    <h3 class="uk-panel-title"></h3>

                   
                </div>
            </div>
            <div class="uk-width-1-4 uk-hidden-small uk-margin-top">
                <div class="uk-panel uk-panel-box uk-panel-box-primary">
                    <h3 class="uk-panel-title"></h3>

                    
                </div>
            </div>
            <!-- First row -->
        @endif

        <div class="uk-width-small-1-1 uk-width-medium-1-2 uk-width-large-1-3 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h3>{{ trans('admin.visit_stats') }}</h3>
                <div class="uk-text-center">
                    <canvas id="listingMessages" width="250" height="250"></canvas>
                    <div id="chartjs-tooltip"></div>
                </div>
            </div>
        </div>
        <div class="uk-width-small-1-1 uk-width-medium-1-2 uk-width-large-1-3 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h3>{{ trans('admin.notifications_dash') }}</h3>
                @if(count($listings->all()) > 0)
                    <ul class="uk-list uk-list-striped">
                        @foreach($listings as $listing)
                            @if($listing->featured_expires_at && $listing->featured_expires_at > Carbon::now())
                                <li><a class="" href="{{ url($listing->pathEdit()) }}">Inmueble #{{ $listing->code }} {{ strtolower(trans('admin.expires')) }} {{ $listing->featured_expires_at->diffForHumans() }}</a></li>
                            @else
                                <li><a class="" href="{{ url($listing->pathEdit()) }}">Inmueble #{{ $listing->code }} {{ strtolower(trans('admin.expires')) }} {{ $listing->expires_at->diffForHumans() }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <div class="uk-text-center">
                        <img src="{{ asset('/images/support/notifications/no_notifications.png') }}" width="70%" style="margin-top:30px">
                    </div>
                @endif
            </div>
        </div>
        <div class="uk-width-small-1-1 uk-width-medium-1-2 uk-width-large-1-3 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h3>{{ trans('admin.unanswered_messages') }}</h3>
                @if(count($messages->all()) > 0)
                    <ul class="uk-list uk-list-striped">
                        @foreach($messages as $message)
                            <li><a class="" href="{{ url('/admin/messages') }}">{{ $message->name }} #{{ $message->listing->code }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <div class="uk-text-center">
                        <img src="{{ asset('/images/support/messages/no_messages.png') }}" width="70%" style="margin-top:30px">
                    </div>
                @endif
            </div>
        </div>

        <div class="uk-width-small-1-1 uk-width-medium-1-2 uk-width-large-1-3 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h3>{{ trans('admin.social_share') }}</h3>
                <img src="{{ asset('/images/support/share.png') }}" style="width:150px; height:150px;" align="left">
                <p>{{ trans('admin.social_share_dash_text') }}</p>
            </div>
        </div>

        <div class="uk-width-small-1-1 uk-width-medium-1-1 uk-width-large-2-3 uk-margin-top">
            <div class="uk-panel uk-panel-box uk-panel-box-primary">
                <h3>{{ trans('admin.get_more_views') }}</h3>
                
                <div class="uk-grid uk-grid-collapse uk-text-center">
                    <div class="uk-width-1-4">
                        <a href="{{ url('/admin/listings') }}"><img src="{{ asset('/images/support/messages/consejo1.png') }}"></a>
                    </div>
                    <div class="uk-width-1-4">
                        <a href="{{ url('/admin/listings') }}"><img src="{{ asset('/images/support/messages/consejo2.png') }}"></a>
                    </div>
                    <div class="uk-width-1-4">
                        <a href="{{ url('/admin/listings') }}"><img src="{{ asset('/images/support/messages/consejo3.png') }}"></a>
                    </div>
                    <div class="uk-width-1-4">
                        <a href="{{ url('/admin/listings') }}"><img src="{{ asset('/images/support/messages/consejo4.png') }}"></a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@stop

@section('js')
    @parent
    <script src="{{ asset('js/Chart.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            // Handler for .ready() called.
            Chart.defaults.global.customTooltips = function(tooltip) {
                // Tooltip Element
                var tooltipEl = $('#chartjs-tooltip');
                // Hide if no tooltip
                if (!tooltip) {
                    tooltipEl.css({
                        opacity: 0
                    });
                    return;
                }
                // Set caret Position
                tooltipEl.removeClass('above below');
                tooltipEl.addClass(tooltip.yAlign);
                // Set Text
                tooltipEl.html(tooltip.text);
                // Find Y Location on page
                var top;
                if (tooltip.yAlign == 'above') {
                    top = tooltip.y - tooltip.caretHeight - tooltip.caretPadding;
                } else {
                    top = tooltip.y + tooltip.caretHeight + tooltip.caretPadding;
                }
                // Display, position, and set styles for font
                tooltipEl.css({
                    opacity: 1,
                    left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
                    top: tooltip.chart.canvas.offsetTop + top + 'px',
                    fontFamily: tooltip.fontFamily,
                    fontSize: tooltip.fontSize,
                    fontStyle: tooltip.fontStyle,
                });
            };


            var data = {!! json_encode($data) !!}

            // Get the context of the canvas element we want to select
            var ctx = document.getElementById("listingMessages").getContext("2d");
            var myNewChart = new Chart(ctx).PolarArea(data, {
                tooltipFontSize: 12,
                tooltipTemplate: "<%if (label){%><%=label%> <%}%><br><b style=\"font-size:14px\"><%= value %> visitas</b>",
                percentageInnerCutout : 1
            });
        });
    </script>
@endsection
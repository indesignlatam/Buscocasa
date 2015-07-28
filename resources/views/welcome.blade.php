@extends('layouts.home')

@section('head')
    <title>{{ Settings::get('site_name') }}</title>
    <meta property="og:title" content="{{ Settings::get('site_name') }}"/>
    <meta property="og:image" content="{{ asset('/images/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('site_description') }}"/>
@endsection

@section('css')
	@parent
    <style type="text/css">
        #code {
          margin: -5px;
          padding: 0;
          text-indent: 1em;

          border-radius: 0 0 0 0;
        }
        #send_button {
          padding: 0;
          border-radius: 0 10px 10px 0;
        }
        #city{
            -webkit-appearance: none;
            -webkit-border-radius: 10px 0 0 10px;
        }
        #category{
            -webkit-appearance: none;
            -webkit-border-radius: 0 0 0 0;
            margin: -5px;
        }
        #type{
            -webkit-appearance: none;
            -webkit-border-radius: 0 0 0 0;
        }
    </style>
@endsection

@section('navbar')
@show

@section('content')
	@include('includes.navbarHome')
	<div class="uk-cover-background uk-position-relative">
        <img class="" src="{{ asset(Image::url($featuredFullScreen->image_path(),['featured_front'])) }}" width="100%" alt="">
        <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-visible-small">
            <h1 class="uk-text-contrast uk-text-bold">{{ trans('frontend.mobile_greeting') }}</h1>
        </div>
	    <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-hidden-small">
            <div class="uk-panel uk-width-6-10" style="margin-top:0px;">
                <h1 class="uk-text-contrast uk-text-bold" style="font-size:50px">{{ strtoupper(trans('frontend.search_properties')) }}</h1>
                <form id="create_form" class="uk-form" method="GET" action="{{ url('/buscar') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <select class="uk-width-2-10 uk-form-large" id="city" name="city_id" placeholder="City" style="width:20%">
                        <option value="">{{ trans('frontend.search_city') }}</option>
                        @foreach($cities as $city)
                            @if($city->id == Request::get('city_id'))
                                <option value="{{ $city->id }}" selected>{{ $city->name }}</option>
                            @else
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endif
                        @endforeach
                    </select>

                    <select class="uk-width-2-10 uk-form-large" id="category" name="category_id" placeholder="Category">
                        <option value="">{{ trans('frontend.search_category') }}</option>
                        @foreach($categories as $city)
                            @if($city->id == Request::get('city_id'))
                                <option value="{{ $city->id }}" selected>{{ str_singular($city->name) }}</option>
                            @else
                                <option value="{{ $city->id }}">{{ str_singular($city->name) }}</option>
                            @endif
                        @endforeach
                    </select>

                    <select class="uk-width-2-10 uk-form-large" id="type" name="listing_type_id" placeholder="Type">
                        <option value="">{{ trans('frontend.search_listing_types') }}</option>
                        @foreach($listingTypes as $city)
                            @if($city->id == Request::get('city_id'))
                                <option value="{{ $city->id }}" selected>{{ $city->name }}</option>
                            @else
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endif
                        @endforeach
                    </select>

                    <input class="uk-form-large" id="code" type="text" name="listing_code" placeholder="{{ trans('frontend.search_field') }}" value="" style="width:25%">

                    <button form="create_form" id="send_button" type="submit" class="uk-button uk-button-primary uk-button-large" style="width:15%">{{ trans('frontend.search_button') }}</button>
                </form>
            </div>
	    </div>
		
	</div>

	<div class="uk-container uk-container-center uk-margin-large-top" id="secondContent">
        <div class="uk-visible-small">
            <h3 class="uk-text-primary">{{trans('frontend.search_properties')}}</h3>
            <form id="create_form" class="uk-form" method="POST" action="{{ url('/search') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" name="city_id" placeholder="City">
                    <option value="">{{ trans('frontend.search_city') }}</option>
                    @foreach($cities as $city)
                        @if($city->id == Request::get('city_id'))
                            <option value="{{ $city->id }}" selected>{{ $city->name }}</option>
                        @else
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endif
                    @endforeach
                </select>

                <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" name="category_id" placeholder="Category">
                    <option value="">{{ trans('frontend.search_category') }}</option>
                    @foreach($categories as $city)
                        @if($city->id == Request::get('city_id'))
                            <option value="{{ $city->id }}" selected>{{ str_singular($city->name) }}</option>
                        @else
                            <option value="{{ $city->id }}">{{ str_singular($city->name) }}</option>
                        @endif
                    @endforeach
                </select>

                <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" name="listing_type_id" placeholder="Type">
                    <option value="">{{ trans('frontend.search_listing_types') }}</option>
                    @foreach($listingTypes as $city)
                        @if($city->id == Request::get('city_id'))
                            <option value="{{ $city->id }}" selected>{{ $city->name }}</option>
                        @else
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endif
                    @endforeach
                </select>

                <input class="uk-width-1-1 uk-margin-small-bottom uk-form-large" type="text" name="listing_code" placeholder="{{ trans('frontend.search_field') }}" value="">

                <button form="create_form" type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1">{{ trans('frontend.search_button') }}</button>
            </form>
        </div>

        <!-- latest listings on sale-->
        @if(count($sales))
            <h1 class="uk-text-bold">{{ trans('frontend.latest_listings_sale') }}</h1>
            <div class="uk-grid">
                @foreach($sales as $sale)
                    <div class="uk-width-large-2-10 uk-width-medium-1-3 uk-width-small-1-1" style="position:relative">

                        @if($sale->featuredType && $sale->featured_expires_at > Carbon::now())
                            <img src="{{ asset($sale->featuredType->image_path) }}" style="position:absolute; top:0; left:30; max-width:100px">
                        @else
                            @if(Carbon::createFromFormat('Y-m-d H:i:s', $sale->created_at)->diffInDays(Carbon::now()) < 5)
                                <img src="{{asset('/images/defaults/new.png')}}" style="position:absolute; top:0; left:30; max-width:100px">
                            @endif
                        @endif

                        <a href="{{ url($sale->path()) }}">
                            <img src="{{ asset(Image::url($sale->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px" data-uk-scrollspy="{cls:'uk-animation-fade'}">
                        </a>
                        <br class="uk-visible-small">
                        <a href="{{ url($sale->path()) }}">{{ $sale->title }}</a>
                        <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $sale->area }} mt2 - {{ money_format('$%!.0i', $sale->price) }}</p>
                        <hr class="uk-visible-small uk-margin-bottom">
                    </div>
                @endforeach
            </div>
            <div class="uk-margin-large-bottom">
                <a href="{{ url('ventas') }}" class="uk-button uk-float-right">Ver más inmuebles</a>
            </div>
        @endif
        <!-- latest listings on sale-->

        <hr>

        <!-- latest listings on lease-->
        @if(count($leases))
            <h1 class="uk-text-bold">{{ trans('frontend.latest_listings_lease') }}</h1>
            <div class="uk-grid">
                @foreach($leases as $lease)
                    <div class="uk-width-large-2-10 uk-width-medium-1-3 uk-width-small-1-1">
                        <a href="{{ url($lease->path()) }}">
                            <img src="{{ asset(Image::url($lease->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px" data-uk-scrollspy="{cls:'uk-animation-fade'}">
                        </a>
                        <a href="{{ url($lease->path()) }}">{{ $lease->title }}</a>
                        <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $lease->area }} mt2 - {{ money_format('$%!.0i', $lease->price) }}</p>
                        <hr class="uk-visible-small uk-margin-bottom">
                    </div>
                @endforeach
            </div>
            <div class="uk-margin-large-bottom ">
                <a href="{{ url('arriendos') }}" class="uk-button uk-float-right">Ver más inmuebles</a>
            </div>
        @endif
        <!-- latest listings on lease-->

        <hr>

        <!-- Featured listings -->
        <h1 class="uk-margin-bottom uk-margin-top uk-text-bold">{{ trans('frontend.featured_listing') }}</h1>

        @if(count($featured) > 0)
    		<div class="uk-grid uk-margin-large-bottom">
                <div class="uk-width-large-3-5 uk-width-small-1-1">
                    <a href="{{ url($featured[0]->path()) }}">
                        <img src="{{ asset(Image::url($featured[0]->image_path(),['mini_image_2x'])) }}" class="uk-margin-remove" data-uk-scrollspy="{cls:'uk-animation-slide-left'}">
                    </a>
                </div>
                <div class="uk-width-large-2-5 uk-width-small-1-1">
                    <a href="{{ url($featured[0]->path()) }}">
                        <h3 class="uk-text-bold">{{ $featured[0]->title }}</h3>
                    </a>
                    <h4 class="uk-margin-top-remove">{{ trans('admin.price') }} <i class="uk-text-primary">{{ money_format('$%!.0i', $featured[0]->price) }}</i></h4>
                    <ul style="list-style-type: none; margin-left:-30px" class="uk-text-muted">
                        <li><i class="uk-icon-check"></i> {{ $featured[0]->rooms }} {{ trans('admin.rooms') }}</li>
                        <li><i class="uk-icon-check"></i> {{ $featured[0]->bathrooms }} {{ trans('admin.bathrooms') }}</li>
                        <li><i class="uk-icon-check"></i> {{ $featured[0]->garages }} {{ trans('admin.garages') }}</li>
                        <li><i class="uk-icon-check"></i> {{ trans('admin.stratum') }} {{ $featured[0]->stratum }}</li>
                        <li><i class="uk-icon-check"></i> {{ $featured[0]->area }} mt2</li>
                        <li id="lot_area"><i class="uk-icon-check"></i> {{ $featured[0]->lot_area }} {{ trans('frontend.lot_area') }}</li>
                        <li><i class="uk-icon-check"></i> {{ money_format('$%!.0i', $featured[0]->administration) }} {{ trans('admin.administration_fees') }}</li>
                    </ul> 
                    <div class="uk-text-muted">
                        {{ str_limit($featured[0]->description, $limit = 250, $end = '...') }}
                    </div>
                </div>
            </div>
        @endif

        @if(count($featured) > 1)
            <div class="uk-grid uk-margin-large-bottom">
                <div class="uk-width-large-2-5 uk-width-small-1-1">
                    <a href="{{ url($featured[1]->path()) }}">
                        <h3 class="uk-text-bold">{{ $featured[1]->title }}</h3>
                    </a>
                    <h4 class="uk-margin-top-remove">{{ trans('admin.price') }} <i class="uk-text-primary">{{ money_format('$%!.0i', $featured[1]->price) }}</i></h4>
                    <ul style="list-style-type: none; margin-left:-30px" class="uk-text-muted">
                        <li><i class="uk-icon-check"></i> {{ $featured[1]->rooms }} {{ trans('admin.rooms') }}</li>
                        <li><i class="uk-icon-check"></i> {{ $featured[1]->bathrooms }} {{ trans('admin.bathrooms') }}</li>
                        <li><i class="uk-icon-check"></i> {{ $featured[1]->garages }} {{ trans('admin.garages') }}</li>
                        <li><i class="uk-icon-check"></i> {{ trans('admin.stratum') }} {{ $featured[1]->stratum }}</li>
                        <li><i class="uk-icon-check"></i> {{ $featured[1]->area }} mt2</li>
                        <li id="lot_area"><i class="uk-icon-check"></i> {{ $featured[1]->lot_area }} {{ trans('frontend.lot_area') }}</li>
                        <li><i class="uk-icon-check"></i> {{ money_format('$%!.0i', $featured[1]->administration) }} {{ trans('admin.administration_fees') }}</li>
                    </ul> 
                    <div class="uk-text-muted">
                        {!! str_limit($featured[1]->description, $limit = 250, $end = '...') !!}
                    </div>
                </div>
                <div class="uk-width-large-3-5 uk-width-small-1-1">
                    <a href="{{ url($featured[1]->path()) }}">
                        <img src="{{ asset(Image::url($featured[1]->image_path(),['mini_image_2x'])) }}" class="uk-margin-remove" data-uk-scrollspy="{cls:'uk-animation-slide-right'}">
                    </a>
                </div>
            </div>
        @endif
        <!-- Featured listings -->
    </div>
</html>
@endsection

@section('js')
	@parent

    <link href="{{ asset('/css/select2front.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('/js/select2.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#city").select2();
        });
    </script>
@endsection
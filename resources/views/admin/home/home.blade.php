@extends('layouts.master')
 
@section('header')
     @parent
@stop
 
@section('content')

<div class="uk-container uk-container-center uk-margin-large-top">
    <h2 class="uk-text-center" style="margin-top:-30px">Dashboard</h2>

    <div class="uk-flex uk-flex-space-around">
        <div class="uk-panel uk-panel-box">
            <a href=""><img src="{{ asset('/images/indesign/logo.png') }}" style="width:150px; height:150px;"></a>
        </div>
        <div class="uk-panel uk-panel-box">
            <a href=""><img src="{{ asset('/images/indesign/logo.png') }}" style="width:150px; height:150px;"></a>
        </div>
        <div class="uk-panel uk-panel-box">
            <a href=""><img src="{{ asset('/images/indesign/logo.png') }}" style="width:150px; height:150px;"></a>
        </div>
        <div class="uk-panel uk-panel-box">
            <a href=""><img src="{{ asset('/images/indesign/logo.png') }}" style="width:150px; height:150px;"></a>
        </div>
        <div class="uk-panel uk-panel-box">
            <a href=""><img src="{{ asset('/images/indesign/logo.png') }}" style="width:150px; height:150px;"></a>
        </div>
    </div>
</div>

@stop
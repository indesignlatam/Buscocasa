@if(isset($mosaicClass) && $mosaicClass)
<div class="{{$mosaicClass}}">
@else
<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small-bottom">
@endif
	<a href="{{ url($listing->path()) }}" style="text-decoration:none">
	@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
    	<div class="uk-panel uk-panel-box" style="border-bottom-width:4px; border-bottom-color:#ff4d53; border-bottom-style: solid;">
    		<div class="uk-overlay uk-overlay-hover">
    			@if($listing->featuredType->id > 1)
					<img src="{{asset($listing->featuredType->image_path)}}" style="position:absolute; top:0px; left:0px; max-width:150px">
				@endif
    @else
    	<div class="uk-panel uk-panel-hover uk-margin-remove">
    		<div class="uk-overlay uk-overlay-hover">
    		@if($listing->created_at->diffInDays(Carbon::now()) < 5)
				<img src="{{asset('/images/defaults/new.png')}}" style="position:absolute; top:15px; left:15px; max-width:90px">
			@endif
    @endif
				<img src="{{ asset(Image::url($listing->image_path(),['mini_image_2x'])) }}" style="width:380px; float:left" class="uk-margin-right">
			    <div class="uk-overlay-panel uk-overlay-background uk-overlay-fade">
			    	<ul style="list-style-type: none;margin-top:-5px" class="uk-text-contrast">
	    				@if($listing->rooms)
	    				<li><i class="uk-icon-check"></i> {{ $listing->rooms }} {{ trans('admin.rooms') }}</li>
	    				@endif

	    				@if($listing->bathrooms)
	    				<li><i class="uk-icon-check"></i> {{ $listing->bathrooms }} {{ trans('admin.bathrooms') }}</li>
	    				@endif

	    				@if($listing->garages)
	    				<li><i class="uk-icon-check"></i> {{ $listing->garages }} {{ trans('admin.garages') }}</li>
	    				@endif

	    				@if($listing->stratum)
	    				<li><i class="uk-icon-check"></i> {{ trans('admin.stratum') }} {{ $listing->stratum }}</li>
	    				@endif

	    				@if($listing->area)
	    				<li><i class="uk-icon-check"></i> {{ number_format($listing->area, 0, ',', '.') }} mt2</li>
	    				@endif

	    				@if($listing->lot_area)
	    				<li id="lot_area"><i class="uk-icon-check"></i> {{ number_format($listing->lot_area, 0, ',', '.') }} {{ trans('frontend.lot_area') }}</li>
	    				@endif

	    				@if((int)$listing->administration != 0)
	    				<li><i class="uk-icon-check"></i> {{ money_format('$%!.0i', $listing->administration) }} {{ trans('admin.administration_fees') }}</li>
	    				@endif
	    			</ul>
			    </div>
			    @if(isset($mosaicClass) && $mosaicClass)
			    <a onclick="unlike()"><i style="position:absolute; top:5px; right:5px" class="uk-icon-heart uk-icon-large uk-text-primary" id="like_button_image"></i></a>
			    @endif
		@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
			</div>
		@else
			</div>
		@endif
    		<div class="">
    			<p class="uk-text-muted"><strong class="uk-text-primary">{{ $listing->title }}</strong> {{ $listing->area }} mts - {{ money_format('$%!.0i', $listing->price) }}</p>
    		</div>
	@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
		</div>
	@else
		</div>
	@endif
	</a>
</div>
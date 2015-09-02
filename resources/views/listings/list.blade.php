<a href="{{ url($listing->path()) }}" style="text-decoration:none">
	<!-- Tags start -->
	@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
		<div class="uk-panel uk-panel-box uk-panel-box uk-margin-bottom" style="border-left-width:4px; border-left-color:{{$listing->featuredType->color}}; border-left-style: solid;">
		<div style="background-color:{{$listing->featuredType->color}}; position:absolute; top:15px; left:15px;" class="uk-text-center uk-text-contrast uk-h3">
			<p class="uk-margin-small-bottom uk-margin-small-top uk-margin-left uk-margin-right"><i class="{{$listing->featuredType->uk_class}}"></i> {{ strtoupper($listing->featuredType->name) }}</p>
		</div>
	@else
		<div class="uk-panel uk-panel-hover uk-margin-remove">
			@if($listing->created_at->diffInDays(Carbon::now()) < 5)
				<div style="background-color:#2089cf; position:absolute; top:15px; left:15px;" class="uk-text-center uk-text-contrast uk-h3">
					<p class="uk-margin-small-bottom uk-margin-small-top uk-margin-left uk-margin-right" style="font-weight:300">{{ trans('frontend.new')}}</p>
				</div>
			@endif
	@endif
	<!-- Tags end -->
		<img src="{{ asset(Image::url($listing->image_path(),['mini_image_2x'])) }}" style="width:350px; max-height:200px; float:left" class="uk-margin-right">
		<div class="uk-visible-small uk-width-1-1 uk-panel"></div>
		<h4 class="uk-margin-remove">{{ $listing->title }}</h4>
		<h4 style="margin-top:0px" class="uk-text-primary">${{ money_format('%!.0i', $listing->price) }}</h4>
		<ul style="list-style-type: none;margin-top:-5px" class="uk-text-muted uk-text-small">
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
@if($listing->featuredType && $listing->featuredType->id > 1 && $listing->featured_expires_at > Carbon::now())
	</div>
@else
	</div>
@endif
</a>
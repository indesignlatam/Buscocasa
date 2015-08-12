<?php namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Image;

class PostListingToFacebookPage extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	protected $listing;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($listing){
		//
		$this->listing = $listing;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(){
		// Post to facebook page
		$page_access_token 		= 'CAALz6NTr0cABAINoFdpijnQzJZAgyOuBnEv90GB3557wU7tanjCZA3QFhkANETGFvO4MD59AZCWB48ME51ZBIhbqdB0ZACeP4FbveQ3Ekf1JuIZCwZA4AFdDH8NOYfhlSOAAvLZC2e6LMU9iNqP3ql62lqOp7Uq5Qro93XZCRloDWpAyfWL1ZBI9weKzQlFEU0SAqgoNoWvAAJTAZDZD';
		$page_id				= '511385855693541';
		$data['picture'] 		= url( Image::url($this->listing->image_path(), ['facebook_share']) );
		$data['link'] 			= url($this->listing->path());
		//$data['message'] 		= "Your message";
		$data['caption'] 		= "Destacados";
		$data['description'] 	= $this->listing->description;
		$data['access_token'] 	= $page_access_token;
		$post_url = 'https://graph.facebook.com/'.$page_id.'/feed';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $post_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$return = curl_exec($ch);
		curl_close($ch);
		// Post to facebook page
	}

}

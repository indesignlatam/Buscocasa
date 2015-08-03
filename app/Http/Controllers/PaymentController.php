<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Carbon, Session, Auth, Log, Settings, URL, Queue;
use App\Models\Payment,
	App\Models\Listing,
	App\Models\FeaturedType;

use App\Commands\SendPaymentConfirmationEmail;
use App\Commands\PostListingToFacebookPage;

class PaymentController extends Controller {

	private $path;

	public function __construct(){
		$this->path = '/admin/pagos/';
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		//
		$query;

		if(!$request->has('unconfirmed')){
			$query = Payment::where('confirmed', true)->where('user_id', Auth::user()->id)->orWhere('canceled', false)->where('user_id', Auth::user()->id);
		}else{
			$query = Payment::where('user_id', Auth::user()->id);
		}
						  
		$payments = $query->paginate(Settings::get('pagination_objects'));

		return view('admin.payments.index', ['payments' => $payments]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request){
		$listing = Listing::find($request->get('listing_id'));

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
					return response()->json(['error' => trans('responses.no_permission')]);
	    		}
	        	return redirect('admin/destacar')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Check id the listing already has a payment that is not confirmed or canceled
		if($listing->hasUnconfirmedPayments()){
			if($request->ajax()){// If request was sent using ajax
				return response()->json(['error' => trans('responses.payment_unconfirmed_for_listing')]);
    		}
        	return redirect('admin/pagos')->withErrors([trans('responses.payment_unconfirmed_for_listing')]);
		}

		$payment		= new Payment;
		$featuredType 	= FeaturedType::find($request->get('featured_id'));

		if(!$featuredType || !$request->get('featured_id')){
			return redirect(URL::previous())->withErrors([trans('admin.no_featured_selected')]);
		}

		
		$input 						= $request->all();
		$input['user_id'] 			= Auth::user()->id;
		$input['reference_code'] 	= md5(Auth::user()->id . $request->get('listing_id') . Carbon::now()->toDateTimeString());
		$input['amount'] 			= floatval(preg_replace("/[^0-9.]*/","", number_format($featuredType->price, 2, '.', ',')));
		$input['tax'] 				= floatval(preg_replace("/[^0-9.]*/","", number_format(($featuredType->price/1.16)*0.16, 2, '.', ',')));
		$input['tax_return_base'] 	= floatval(preg_replace("/[^0-9.]*/","", number_format($featuredType->price/1.16, 2, '.', ',')));
		$input['description'] 		= $featuredType->name . ', ' . $listing->title . ' - ' . $listing->code;

		// Data to create signature
		$referenceCode 				= $input['reference_code'];
		$amount 					= $input['amount'];
		$currency 					= Settings::get('currency', 'COP');
		$merchantId;
		$apiKey;

		if(Settings::get('payu_test', 1)){
			$merchantId 				= config('payu.test_merchant_id');
			$apiKey 					= config('payu.test_api_key');
		}else{
			$merchantId 				= config('payu.merchant_id');
			$apiKey 					= config('payu.api_key');
		}
		
		$signature 					= str_replace(',', '.', "$apiKey~$merchantId~$referenceCode~$amount~$currency");

		$input['signature'] 		= md5($signature);

		if (!$payment->validate($input)){
	        return redirect($this->path.$request->get('listing_id'))->withErrors($payment->errors())->withInput();
	    }

		$payment = $payment->create($input);

		return redirect($this->path.$payment->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id, Request $request){
		//
		$payment = Payment::where('id', $id)->where('locked', false)->where('confirmed', false)->where('canceled', false)->first();// TODO eager loading?

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$payment || $payment->user->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
					return response()->json(['error' => trans('responses.no_permission')]);
	    		}
	        	return redirect($this->path)->withErrors([trans('responses.no_permission')]);
	    	}
		}
		
		if($payment){
			$payment->locked = true;
			$payment->save();
		}

		return view('admin.payments.show', ['payment' => $payment]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function payUResponse(Request $request){
		//
		$apiKey;
		if(Settings::get('payu_test', 1)){
			$apiKey 					= config('payu.test_api_key');
		}else{
			$apiKey 					= config('payu.api_key');
		}

		$merchantId 		= $request->get('merchantId');
		$referenceCode 		= $request->get('referenceCode');
		$amount				= number_format($request->get('TX_VALUE'), 1, '.', '');
		$currency			= $request->get('currency');
		$transactionState	= $request->get('transactionState');

		$signature 	= "$apiKey~$merchantId~$referenceCode~$amount~$currency~$transactionState";
		$signature 	= md5($signature);

		if($signature != $request->get('signature')){
			return redirect('/admin/pagos')->withErrors([trans('admin.payment_signature_error')]);
		}

		$payment 	= Payment::where('reference_code', $referenceCode)->where('user_id', Auth::user()->id)->first();
		$listing 	= $payment->listing;

		if(!$listing){
			return redirect('/admin/pagos')->withErrors([trans('admin.payment_no_listing_error')]);
		}

		return view('admin.payments.response', ['signature' => $signature,
												'listing' 	=> $listing,
												'payment'	=> $payment,
												]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function confirm(Request $request){
		//
		$apiKey;
		if(Settings::get('payu_test', 1)){
			$apiKey 					= config('payu.test_api_key');
		}else{
			$apiKey 					= config('payu.api_key');
		}
		
		$merchantId 		= $request->get('merchant_id');
		$referenceCode 		= $request->get('reference_sale');
		$amount				= number_format($request->get('value'), 2, '.', '');
		$currency			= $request->get('currency');
		$transactionState	= $request->get('state_pol');

		// Log::info('Amount decimals: '.substr($amount, -2));

		if(substr($amount, -2) == "00"){
			// Log::info('Decimals == 00');
			$amount	= number_format($amount, 1, '.', '');
		}

		$signature 	= "$apiKey~$merchantId~$referenceCode~$amount~$currency~$transactionState";
		$signature 	= md5($signature);

		// Log::info('Signature:');
		// Log::info($signature);
		// Log::info($request->get('sign'));

		if($signature != $request->get('sign')){
			return response()->json(['error' => 'Invalid signature'], 401);
		}

		$payment = Payment::where('reference_code', $request->get('reference_sale'))->first();

		if(!$payment->confirmed){
			// Log::info('Payment state: '.$request->get('state_pol'));
			$payment->locked 	= false;
			
			$payment->state_pol 			= $request->get('state_pol');
			$payment->risk 					= $request->get('risk');
			$payment->response_code_pol 	= $request->get('response_code_pol');
			$payment->reference_pol 		= $request->get('reference_pol');
			$payment->transaction_date 		= $request->get('transaction_date');
			$payment->cus 					= $request->get('cus');
			$payment->pse_bank 				= $request->get('pse_bank');
			$payment->authorization_code 	= $request->get('authorization_code');
			$payment->bank_id 				= $request->get('bank_id');
			$payment->ip 					= $request->get('ip');
			$payment->payment_method_id 	= $request->get('payment_method_id');
			$payment->transaction_bank_id 	= $request->get('transaction_bank_id');
			$payment->transaction_id 		= $request->get('transaction_id');
			$payment->payment_method_name 	= $request->get('payment_method_name');

			if($request->get('state_pol') == 4){
				$payment->confirmed = true;
				$payment->canceled 	= false;

				// Update the listing and add it 30 days more of featuring 
				// TODO If user pays again but changes the type the type will change and add the time
				$payment->listing->featured_type 	= $payment->featuredType->id;
				if($payment->listing->featured_expires_at && $payment->listing->featured_expires_at < Carbon::now()){
					$payment->listing->featured_expires_at 	= $payment->listing->featured_expires_at->addDays(30);
				}else{
					$payment->listing->featured_expires_at 	= Carbon::now()->addDays(30);
				}
				$payment->listing->expires_at 		=  $payment->listing->featured_expires_at->addDays(10);
				$payment->listing->save();

				// Send confirmation email to user and generate billing
				Queue::push(new SendPaymentConfirmationEmail($payment));

				// Post to facebook page
				Queue::push(new PostListingToFacebookPage($payment->listing));
			}

			$payment->save();
		}

		return response()->json(['success' => true]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){
		//
	}

	/**
	 * Cancel the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function cancel($id, Request $request){
		//
		$payment = Payment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$payment || $payment->user->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
					Session::flash('errors', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
				}
				// If nos usign ajax return redirect
	        	return redirect($this->path)->withErrors([trans('responses.no_permission')]);
	    	}
		}

		if($payment->confirmed || $payment->canceled || $payment->state_pol){
			if($request->ajax()){// If request was sent using ajax
				Session::flash('errors', [trans('responses.cant_cancel_payment')]);
				return response()->json(['error' => trans('responses.cant_cancel_payment')]);
			}
	        return redirect($this->path)->withErrors([trans('responses.cant_cancel_payment')]);
		}

		$payment->canceled = true;
		$payment->save();

		if($request->ajax()){// If request was sent using ajax
			Session::flash('errors', [trans('responses.payment_canceled')]);
			return response()->json(['success' => trans('responses.payment_canceled')]);
		}
		// If nos usign ajax return redirect
		return redirect($this->path)->withSuccess([trans('responses.payment_canceled')]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request){
		//
		$payment = Payment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$payment || $payment->user->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
					Session::flash('errors', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
				}
				// If nos usign ajax return redirect
	        	return redirect($this->path)->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$payment->canceled = true;
		$payment->save();

		if($request->ajax()){// If request was sent using ajax
			Session::flash('success', [trans('responses.payment_canceled')]);
			return response()->json(['success' => trans('responses.payment_canceled')]);
		}
		// If nos usign ajax return redirect
		return redirect($this->path)->withSuccess([trans('responses.payment_canceled')]);
	}

}
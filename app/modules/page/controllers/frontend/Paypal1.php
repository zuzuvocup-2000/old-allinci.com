<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \PayPal\Api\Payment;
class Paypal1 extends MY_Controller {
	private $clientId;
	private $secret;
	private $mode;
	function __construct() {
		parent::__construct();
		$this->clientId = 'AfgV8IaTpMRV-wqU3wDNZLf3xbWmd3ZTv-qJiGyHPZZ7WRdZFInnJBpmdfP817kP_JVZPTyDH0eIiq9Y';
		$this->secret = 'EKGM0yAeQRvYeXXy_vQ0olERPbi7MYfN1QBHc29UJ8ai6iPJBXBKhRUa96-qri36iVb5cSe8jEPuZNoO';
		$this->mode ='sandbox';
		$url = substr(APPPATH,0,-4);
		require($url.'plugin/vendor1/autoload.php');
	}
	
	public function index(){
		$paypal = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential($this->clientId,$this->secret)
		);
		$paypal->setConfig(array(
			'mode' => $this->mode,
		));

		/* GET Thông tin từ form */
		$fullname = $this->input->post('fullname');
		$email = $this->input->post('email');
		$quantity = 1;
		/* -------------------- */
		
		//tên đơn hàng
		$product = 'title'; 
		// Tôgnr giá tịd dơ hàng
		$price = 100;
		$shipping = 0.00;
		// số lượng 1
		$total = $price*$quantity + $shipping;
		
		
		$payer = new \PayPal\Api\Payer();
		$payer->setPaymentMethod('paypal');
	
		$item = new \PayPal\Api\Item();
		$item->setName($product)
		// this-. curent úd
		->setCurrency('USD')
		->setQuantity($quantity)
		->setPrice($price);
		
		
		
		$itemList = new \PayPal\Api\ItemList();
		$itemList->setItems(array($item));
		
		$details = new \PayPal\Api\Details();
		$details->setShipping($shipping)
		->setSubtotal($price*$quantity);
		
		$amount = new \PayPal\Api\Amount();
		$amount->setCurrency('USD')
		->setTotal($total)
		->setDetails($details);
		
		$transaction = new \PayPal\Api\Transaction();
		$transaction->setAmount($amount)
		->setItemList($itemList)
		->setDescription('Payment for '.$product.'')
		->setInvoiceNumber(uniqid());
		
		
		$redirectUrls = new \PayPal\Api\RedirectUrls();
		$redirectUrls->setReturnUrl(BASE_URL.'paypal/frontend/paypal/success?success=true&id='.'1'.'')
		->setCancelUrl(BASE_URL.'paypal/frontend/paypal/success?success=false'); 
		
		$payment = new \PayPal\Api\Payment();
		
		$payment->setIntent('sale')
		->setPayer($payer)
		->setRedirectUrls($redirectUrls)
		->setTransactions(array($transaction));
		
		try {
			$payment->create($paypal);
			// echo 1;die();
		} catch (PayPal\Exception\PayPalConnectionException $ex) {
			echo $ex->getCode(); // Prints the Error Code
			echo $ex->getData(); // Prints the detailed error message 
			die($ex);
		} catch (Exception $ex) {
			echo $ex->getCode(); // Prints the Error Code
			// echo $ex->getData(); // Prints the detailed error message 
			pre($ex);
		}
		
		$approvaUrl = $payment->getApprovalLink();
		header("Location:{$approvaUrl}");	
	}
	
	
}

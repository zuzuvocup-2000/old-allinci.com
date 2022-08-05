<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
class Paypal extends MY_Controller {
	private $clientId;
	private $secret;
	private $mode;
	function __construct() {
		parent::__construct();
		$this->clientId = 'AfgV8IaTpMRV-wqU3wDNZLf3xbWmd3ZTv-qJiGyHPZZ7WRdZFInnJBpmdfP817kP_JVZPTyDH0eIiq9Y';
		$this->secret = 'EKGM0yAeQRvYeXXy_vQ0olERPbi7MYfN1QBHc29UJ8ai6iPJBXBKhRUa96-qri36iVb5cSe8jEPuZNoO';
		$this->mode ='sandbox';
		$this->money ='USD';
		$url = substr(APPPATH,0,-4);
		require($url.'plugin/vendor/autoload.php');
		
	}
	
	public function index(){
		$apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				$this->clientId,
				$this->secret
			)
		);
		$apiContext->setConfig(array(
			'mode' => $this->mode,
		));

		// Create new payer and method
		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		// Set redirect URLs
		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl(BASE_URL.'payment/frontend/paypal/success?success=true')
		  ->setCancelUrl(BASE_URL.'payment/frontend/paypal/success?success=false');

		// Set payment amount
		$amount = new Amount();
		$amount->setCurrency($this->money)
		  ->setTotal('12311');	
		  
		 // Set transaction object
		$transaction = new Transaction();
		$transaction->setAmount($amount)
		  ->setDescription('Thanh toán cho đơn hàng: '.'abc'.'');

		// Create the full payment object
		$payment = new Payment();
		$payment->setIntent('sale')
		  ->setPayer($payer)
		  ->setRedirectUrls($redirectUrls)
		  ->setTransactions(array($transaction));


		// Create payment with valid API context
		try {
		  $payment->create($apiContext);
		  // Get PayPal redirect URL and redirect the customer
		  $approvalUrl = $payment->getApprovalLink();
		  // Redirect the customer to $approvalUrl
		} catch (PayPal\Exception\PayPalConnectionException $ex) {
		  echo $ex->getCode();
		  echo $ex->getData();
		  die($ex);
		} catch (Exception $ex) {
		  die($ex);
		}
		$approvaUrl = $payment->getApprovalLink();
		header("Location:{$approvaUrl}");	

		
	}
	public function success(){
		$success = $this->input->get('success');
		$orderId = $this->input->get('orderId');
		if($success == 'true'){
			$_update = array(
				'paymentCataId' => 4,
				'paymentid' => $this->input->post('paymentId'),
				'payerid' => $this->input->post('PayerID'),
				'status' => 'processing',
			);
		}else{
			$_update = array(
				'status' => 'pending_payment',
			);
		}
		// cập nhật đơn hàng
		$flag = $this->Autoload_Model->_update(array(
			'where' => array('id' => $orderId),
			'table' => 'order',
			'data' => $_update,
		));

		// xóa cookie
		// delete_cookie("cart_contents");

		// thêm cookie đặt đơn hàng thành công
		setcookie('orderid', $orderId, time() + 3600, '/');
		// chuyển về trang thanh toàn thành công
	}
	
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	
	public function get_data_month(){
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$date = $year.'-'.$month.'-01';
		// First day of the month.
		$first_date = date('Y-m-01', strtotime($date));
		// Last day of the month.
		$end_date = date('Y-m-t', strtotime($date));

		$bookingRela = $this->Autoload_Model->_get_where(array(
			'select' => 'tb1.start, tb1.end, tb1.status, tb2.date',
			'table' => 'booking as tb1',
			'query' => 'tb2.date <="'.$end_date.'" AND tb2.date >="'.$first_date.'"',
			'join' => array(
				array('booking_catalogue as tb2', 'tb1.catalogueid = tb2.id', 'inner'),
			),
		), true);
		$temp = '';
		if(isset($bookingRela) && check_array($bookingRela)){
			foreach ($bookingRela as $keyRela => $valRela) {
				$H = substr( $valRela['start'], 0, 2);
				$i = substr( $valRela['start'], 3, 5);
				$time_start = ', '.$H.', '.$i;

				$H = substr( $valRela['end'], 0, 2);
				$i = substr( $valRela['end'], 3, 5);
				$time_end = ', '.$H.', '.$i;

				$first_date = strtotime(gettime($valRela['date'],'Y-m-d'));
			    $second_date = strtotime(gettime($this->currentTime, 'Y-m-d'));
			    $datediff = $first_date - $second_date;
			   	$day = $datediff / (60*60*24);
			   	if($day > 0){
			   		$date = 'y, m, d+'.abs($day);
			   	}else{
			   		$date = 'y, m, d-'.abs($day);
			   	}
			   	$temp = $temp.'{title: "",start: new Date('.$date.$time_start.'),end: new Date('.$date.$time_end.'),allDay: false},';
			}
		}
		echo $temp;die;
	}

}

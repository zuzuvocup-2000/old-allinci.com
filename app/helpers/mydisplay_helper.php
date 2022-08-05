<?php 

// thiết lập hiểm thị
if(!function_exists('manage_display_html')){
	function manage_display_html($publish = 1){
		$CI =& get_instance();
		$html = '';
		$html .= '<div class="form-row">';
			$html .= '<span class="text-black mb15">Quản lý thiết lập hiển thị cho blog này.</span>';
			$html .= '<div class="block clearfix">';
				$html .= '<div class="i-checks mr30" style="width:100%;"><span style="color:#000;"> <input type="radio" '.(($CI->input->post('publish') == '1' || ($publish == 1 && null == $CI->input->post('publish'))) ? 'checked' : '').' class="popup_gender_1 gender" value="1"  name="publish"> <i></i> Cho phép hiển thị trên website</span></div>';
			$html .= '</div>';
			$html .= '<div class="block clearfix">';
				$html .= '<div class="i-checks" style="width:100%;"><span style="color:#000;"> <input type="radio" '.(($CI->input->post('publish') == '0' || ($publish == 0 && null == $CI->input->post('publish'))) ? 'checked' : '').'  class="popup_gender_0 gender" value="0" name="publish"> <i></i> Không cho phép hiển thị trên website. </span></div>';
			$html .= '</div>';
		$html .= '</div>';
		
		
		
		return $html;
	}
}

// quốc gia
if(!function_exists('manage_nation_html')){
	function manage_nation_html($regionid = 0){
		$CI =& get_instance();
		$html = '';
		$html .= '<div class="form-row">';
			$html .= '<div class="block clearfix">';
				$html .= '<div class="i-checks" style="width:100%;"><span style="color:#000;"> <input '.(($CI->input->post('regionid') == '0' || ($regionid == 0 && null == $CI->input->post('regionid'))) ? 'checked' : '').' class="popup_gender_0 region" type="radio" value="0"  name="regionid"> Trong nước</span></div>';
			$html .= '</div>';
			
			$html .= '<div class="block clearfix">';
				$html .= '<div class="i-checks" style="width:100%;"><span style="color:#000;"> <input '.(($CI->input->post('regionid') == '1' || ($regionid == 1 && null == $CI->input->post('regionid'))) ? 'checked' : '').' class="popup_gender_1 region" type="radio" value="1"  name="regionid"> Quốc tế</span></div>';
			$html .= '</div>';
			
		$html .= '</div>';
		
		return $html;
	}
}
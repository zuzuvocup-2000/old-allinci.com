<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend extends MY_Controller {

	public function __construct(){
		parent::__construct();
        $this->load->library(array('configbie'));
	}
	public function get_list_prd_cat(){
		$this->load->helper(array('myfrontendcommon'));
        $json = [];
        $data['from'] = 0;
        $data['to'] = 0;
        $listPerpage = $this->configbie->data('perpage_frontend');
        $perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : current($listPerpage);
        $page = ($this->input->get('page')) ? $this->input->get('page') : '';
        $keyword = $this->db->escape_like_str($this->input->get('keyword'));
        $param['catalogueid'] = ($this->input->get('catalogueid')) ? $this->input->get('catalogueid') : '';
        $param['brand'] = ($this->input->get('brand')) ? $this->input->get('brand') : '';
        $param['attr'] = ($this->input->get('attr')) ? $this->input->get('attr') : '';
        if(isset($param['attr']) ){
            $attr = explode(';',$param['attr']) ; 
            foreach ($attr as $key => $val) {
                if ($key % 2 == 1){
                    if($val != '' ){
                        $data['attrList'][] = $val;
                    }
                }
            }
        }
        $param['min_price'] = ($this->input->get('min_price')) ? $this->input->get('min_price') : $data['min_price'];
        $param['max_price'] = ($this->input->get('max_price')) ? $this->input->get('max_price') : $data['max_price'];
        $param['sort'] = ($this->input->get('sort')) ? $this->input->get('sort') : '';
        $detailCatalogue = $this->Autoload_Model->_get_where(array(
            'select' => 'id, title, level, lft, rgt, parentid, brand_json, attrid, canonical,description, image',
            'table' => 'product_catalogue',
            'query' => 'id = '.$param['catalogueid'],
        ));
        $data['post_min_price'] = (int)$param['min_price'];
        $data['post_max_price'] = (int)$param['max_price'];

        $query = '';
        $query = $query.' AND tb1.price >= '.$param['min_price'].' AND tb1.price <= '.$param['max_price'].' ';
        $order_by = ' tb1.order ASC';
        if(isset($param['sort']) && $param['sort'] != 0 ){
            $sort = explode('|', $param['sort']);
            $order_by =  ' tb1.'.$sort[0].' '.$sort[1].', '.$order_by. ', ';
        }

        if(!empty($param['catalogueid'])){
            $query = $query.' AND tb3.catalogueid =  '.$param['catalogueid'];
        }
        if(!empty($param['brand'])){
            $str_brand= '';
            foreach ($param['brand'] as $key => $value) {
                $str_brand = $str_brand.$value.', ';
            }
            $str_brand = substr( $str_brand, 0, strlen($str_brand) -2);
            $str_brand = '('.$str_brand.')';
            $query = $query.' AND tb1.brandid IN  '.$str_brand;
        }
        // xử lí điều kiện lọc thuộc tính
        if(!empty($param['attr'])){
            $attr = explode(';',$param['attr']) ;
            foreach ($attr as $key => $val) {
                if ($key % 2 == 0){
                    if($val != '' ){
                        $attribute[$val][] = $attr[$key +1];
                    }
                }else{
                    continue;
                }
            }
            $total = 0;
            $index = 100;
            foreach ($attribute as $key => $val) {
                $attribute_catalogue = $this->Autoload_Model->_get_where(array(
                    'select' =>'id',
                    'table' =>'attribute_catalogue',
                    'where'=> array('keyword'=> $key),
                ));
                $query = $query.' AND ( ';
                $total++;
                $index ++;
                foreach ($val as $sub => $subs) {
                    $index = $index + $total; 
                    $query = $query.' tb_attr_'.$index.'.attrid =  '.$subs.' OR ';
                    $json[] = array('attribute_relationship as tb_attr_'.$index, 'tb1.id = tb_attr_'.$index.'.moduleid AND tb_attr_'.$index.'.module ="product"', 'inner');
                }
                $query = substr( $query,  0, strlen($query) -3 );
                $query = $query.' ) ';
            }
            // $query = $query.' GROUP BY `tb_attr_102`.`moduleid`';
        }
        $json[] = array('catalogue_relationship as tb3', 'tb1.id = tb3.moduleid AND tb3.module = "product"', 'inner');
        $json[] = array('promotional_relationship as tb2', 'tb1.id = tb2.moduleid AND tb2.module = "product"', 'left');

        $query = substr( $query,  4, strlen($query));

        $config['total_rows'] = $this->Autoload_Model->_get_where(array(
            'select' => 'tb1.id',
            'table' => 'product as tb1',
            'where' => array('tb1.publish' => 0),
            'keyword' => $keyword,
            'join' => $json,
            'query' => $query,
            'distinct' => 'true',
            'count' =>TRUE,
        ));
        
        $config['base_url']  = '';
        if($config['total_rows'] > 0){
            $this->load->library('pagination');

            $config['base_url'] = rewrite_url($detailCatalogue['canonical'], false, true) ;
            $config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');

            $config['prefix'] = 'trang-';

            $config['first_url'] = $config['base_url'].$config['suffix'];
            $config['per_page'] = $perpage;
            $config['uri_segment'] = 2;
            $config['use_page_numbers'] = TRUE;
            $config['full_tag_open'] = '<ul class="uk-pagination uk-pagination-right">';
            $config['full_tag_close'] = '</ul>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="uk-active"><a>';
            $config['cur_tag_close'] = '</a></li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $data['PaginationList'] = $this->pagination->create_links();
            $totalPage = ceil($config['total_rows']/$config['per_page']);
            $page = ($page <= 0)?1:$page;
            $page = ($page > $totalPage)?$totalPage:$page;
            $seoPage = ($page >= 2) ? '-Trang '.$page : '';
            if($page >= 2){
                $data['canonical'] = $config['base_url'].'/trang-'.$page.$this->config->item('url_suffix');
            }
            $page = $page - 1;
            $data['from'] = ($page * $config['per_page']) + 1;
            $data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
            $productList = $this->Autoload_Model->_get_where(array(
                'select' => 'tb1.id, tb1.id as productid, tb1.title, tb1.canonical, tb1.price, tb1.price_sale, tb1.price_contact, tb1.image, tb1.version_json, tb1.image_color_json, tb2.end_date',
                'table' => 'product as tb1',
                'where' => array('tb1.publish' => 0),
                'limit' => $config['per_page'],
                'start' => $page * $config['per_page'],
                'keyword' => $keyword,
                'join' => $json,
                'query' => $query,
                'distinct' => 'true',
                'order_by' => $order_by,
            ), true);
            $productList = getDetailListPrd(array('productList' => $productList));
            
            foreach ($productList as $key => $value) {
                $commnet = comment(array('id' => $value['productid'], 'module' => 'product'));
                $productList[$key]['rate'] = '';
                $productList[$key]['totalComment'] = '';
                if(isset($commnet) && is_array($commnet) && count($commnet)){
                    $productList[$key]['totalComment'] = round($commnet['statisticalRating']['totalComment']);
                    $productList[$key]['rate'] = round($commnet['statisticalRating']['averagePoint']);
                }
                $productList[$key]['info'] = getPriceFrontend(array('productDetail' => $value));
            }
        }

        $html = '';

        if(isset($productList) && is_array($productList) && count($productList)){ 
            foreach($productList as $key => $val){
                $title = $val['title'];
                $rate = $val['rate'];
                $totalComment = $val['totalComment'];
                $image = $val['image'];
                $href = rewrite_url($val['canonical']);
                $info = $val['info'];
                $end_date = '';
                if(isset($val['end_date'])){
                    $end_date  =  $val['end_date'] ;
                }
            

                $html = $html.'<div class="product uk-clearfix">';
                    $html = $html.'<div class="thumb">';
                        if($info['percent'] != 0){
                            $html = $html.'<div class="percent">-'.$info['percent'].'</div>';
                        }
                        $html = $html.'<a href="'.$href.'" title="'.$title.'" class="image img-scaledown img-shine"><img  src="'.$image.'" alt="" /></a>';
                        $html = $html.renderCountDown($end_date);
                    $html = $html.'</div>';
                    $html = $html.'<div class="info">';
                        $html = $html.'<h3 class="title"><a href="'.$href.'" title="'.$title.'">'.$title.'</a></h3>';

                        $html = $html.'<div class="product-ratings">';
                            $html = $html.'<div class="rating-box">';
                                    $html = $html.'<ul class="uk-list uk-clearfix uk-flex uk-flex-middle d-flex">';
                                        for($e = 1; $e <= $rate; $e++){
                                            $html = $html.'<li class="rating-true"><i class="fa fa-star"></i></li>';
                                        }
                                        for($e = 1; $e <= (5 - $rate); $e++){
                                            $html = $html.'<li class="rating-false"><i class="fa fa-star"></i></li>';
                                        }
                                        $html = $html.'('.$totalComment.')';
                                    $html = $html.'</ul>';
                            $html = $html.'</div>';
                        $html = $html.'</div>';
                        $html = $html.'<div class="product-price">';
                            if($info['flag'] == 1){
                                $html = $html.'<div class="new-price text-center"> '.$info['price_final'].'</div>';
                            }else{
                                $html = $html.'<div class="uk-grid uk-grid-collaps">';
                                    $html = $html.'<div class="uk-width-1-2">';
                                        $html = $html.'<div class="old-price text-center">'.$info['price_old'].'</div>';
                                    $html = $html.'</div>';
                                    $html = $html.'<div class="uk-width-1-2">';
                                        $html = $html.'<div class="new-price text-center">'.$info['price_final'].'</div>';
                                    $html = $html.'</div>';
                                $html = $html.'</div>';
                            }
                        $html = $html.'</div>';
                   $html = $html.' </div>';
                $html = $html.'</div>';
            }
        }
        echo json_encode(array(
            'pagination' => (isset($data['PaginationList'])) ? $data['PaginationList'] : '',
            'html' => (isset($html)) ? $html : '',
            'from' => $data['from'],
            'to' => $data['to'],
            'total_row' => $config['total_rows'],
        ));die();

        
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {
    public $module;
    function __construct() {
        parent::__construct();
        $this->load->library(array('configbie'));
        $this->load->helper(array('myfrontendcommon'));
        $this->load->library('nestedsetbie', array('table' => 'product_catalogue'));
    }
    public function view($catalogueid = 0, $page = 1){
        $catalogueid = (int)$catalogueid;
        $seoPage = '';

        $product = $this->Autoload_Model->_get_where(array(
            'select' => 'MAX(tb1.price) as max_price, MIN(tb1.price) as min_price',
            'table' => 'product as tb1',
            'where' => array('tb1.publish' => 0),
            'join' => array(array('catalogue_relationship as tb3', 'tb1.id = tb3.moduleid AND tb3.module = "product"', 'inner')),
            'query' => 'tb3.catalogueid = '.$catalogueid,
            'distinct' => 'true',
        ));

        $data['min_price'] = ($product['min_price'] != '') ? $product['min_price'] : 0;
        $data['max_price'] = ($product['max_price'] != '') ? $product['max_price'] : 0;


        $json = [];
        $data['from'] = 0;
        $data['to'] = 0;
        $listPerpage = $this->configbie->data('perpage_frontend');
        $perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : current($listPerpage);
        $page = ($this->input->get('page')) ? $this->input->get('page') : $page;
        $keyword = $this->db->escape_like_str($this->input->get('keyword'));
        $param['catalogueid'] = ($this->input->get('catalogueid')) ? $this->input->get('catalogueid') : $catalogueid;
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
            'select' => 'id, title, level, lft, rgt, parentid, brand_json, image_json, attrid, canonical,description, image, icon',
            'table' => 'product_catalogue',
            'query' => 'id = '.$param['catalogueid'],
        ));

        if(!isset($detailCatalogue) || is_array($detailCatalogue) == false || count($detailCatalogue) == 0){
            $this->session->set_flashdata('message-danger', 'Danh mục sản phẩm không tồn tại');
            redirect(BASE_URL);
        }

        $data['post_min_price'] = $param['min_price'];
        $data['post_max_price'] = $param['max_price'];

        $query = '';


        $order_by = ' tb1.order desc';
        if(!empty($param['sort']) && $param['sort'] !=0 ){
            pre($param['sort']);
            $sort = explode('|', $param['sort']);
            $order_by =  'tb1.'.$sort[0].' '.$sort[1].', '.$order_by.' , ';
        }


        if(!empty($param['catalogueid'])){
            $temp = $this->Autoload_Model->_get_where(array(
                'select' => 'id, attrid',
                'table' => 'product_catalogue',
                'query' => 'lft >= '.$detailCatalogue['lft'].' AND '.'rgt <= '.$detailCatalogue['rgt'],
            ), true);

            $attrList = getColumsInArray($temp, 'attrid');
            $cataList = getColumsInArray($temp, 'id');
            $str_cata = '';
            if(isset($cataList) && is_array($cataList) && count($cataList)){
                foreach ($cataList as $key => $val) {
                    $str_cata = $str_cata.$val.', ';
                }
            }
            $str_cata = substr( $str_cata, 0, strlen($str_cata) -2);
            $str_cata = '('.$str_cata.')';

            $query = $query.' AND tb3.catalogueid IN  '.$str_cata;
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


        // xử lý filter

        $filter = ($this->input->get('filter'))? $this->input->get('filter') : '';

        if (!empty($filter)) {
            $filter = explode('|',  $filter);
            // print_r($filter); 
            if(isset($filter) && is_array($filter) && count($filter)){
                if($filter[0] == 'id' && $filter[1] != ''){
                    $order_by = 'tb1.id '.$filter[1];
                }
                if($filter[0] == 'price_2' && $filter[1] != ''){
                    $order_by = 'tb1.price '.$filter[1];
                }
            }
        }

        $query = substr( $query,  4, strlen($query));

        // print_r($filter); exit;

        $json[] = array('catalogue_relationship as tb3', 'tb1.id = tb3.moduleid AND tb3.module = "product"', 'full');
        $json[] = array('promotional_relationship as tb2', 'tb1.id = tb2.moduleid AND tb2.module = "product"', 'left');


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
            $config['full_tag_open'] = '<div class="pagination uk-text-left"><ul class="uk-pagination">';
            $config['full_tag_close'] = '</ul></div>';
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
                'select' => 'tb1.id, tb1.id as productid, tb1.title, tb1.canonical, tb1.price, tb1.price_sale, tb1.price_contact, tb1.image, tb1.version_json, tb1.image_color_json, tb2.end_date, tb1.description, tb1.code,
                    (SELECT title FROM product_catalogue WHERE product_catalogue.id = tb1.catalogueid) as catalogue_title
                ',
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
            $data['productList'] = $productList;
        }

        $data['total_row'] = $config['total_rows'];
        $data['perpage'] = $perpage;

        // xử lí đường dẫn tới sản phẩm
        $data['breadcrumb'] = $this->Autoload_Model->_get_where(array(
            'select' => 'id, title, slug, canonical, lft, rgt',
            'table' => 'product_catalogue',
            'where' => array('lft <=' => $detailCatalogue['lft'],'rgt >=' => $detailCatalogue['lft']),
            'limit' => 1,
            'order_by' => 'lft ASC, order ASC'
        ), TRUE);


        $data['meta_title'] = (!empty($detailCatalogue['meta_title'])?$detailCatalogue['meta_title']:$detailCatalogue['title']).$seoPage;
        $data['meta_description'] = (!empty($detailCatalogue['meta_description'])?$detailCatalogue['meta_description']:cutnchar(strip_tags($detailCatalogue['description']), 255)).$seoPage;
        $data['meta_image'] = !empty($detailCatalogue['image'])?base_url($detailCatalogue['image']):'';
        if(!isset($data['canonical']) || empty($data['canonical'])){
            $data['canonical'] = $config['base_url'].$this->config->item('url_suffix');
        }

        // sửa lại biến attrid cũ
        //  vì đây là thuộc tính của cả nhóm danh mục con
        $temp = [];
        if(isset($attrList) && check_array($attrList)){
            foreach ($attrList as $key => $val) {
                $temp1 = json_decode($val, true);
                if(isset($temp1) && check_array($temp1)){
                    foreach ($temp1 as $sub => $subs) {
                        $temp[$sub] = (isset($temp[$sub])) ? array_merge($temp[$sub], $subs) : $subs;
                    }
                }
            }
        }
        $data['attribute'] = getListAttr( (check_array($temp)) ? json_encode($temp) : '');
        $data['detailCatalogue'] = $detailCatalogue;
        $data['template'] = 'product/frontend/catalogue/view';
        $this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);

    }


}

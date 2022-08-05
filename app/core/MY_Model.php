<?php
(defined('BASEPATH')) or exit('No direct script access allowed');

class MY_Model extends CI_Model {
	
	
	public function __construct(){
		parent::__construct();
		
	}
	
	public function _get_where($param = '', $flag = FALSE){
		$param['table'] = isset($param['table']) ? $param['table'] : '';
		$param['select'] = isset($param['select']) ? $param['select'] : 'id';
		$param['start'] = isset($param['start']) ? $param['start'] : 0;
		$param['where_in_field'] = isset($param['where_in_field']) ? $param['where_in_field'] : 'id';
		
		$this->db->select($param['select']);
		$this->db->from($param['table']);

		if(isset($param['keyword']) && !empty($param['keyword'])){
			$keyword = $this->db->escape_like_str($param['keyword']);
			$this->db->where($param['keyword']);
		}
		if(isset($param['group_by']) && !empty($param['group_by'])){
			$this->db->group_by($param['group_by']); 
		}
		if(isset($param['having']) && !empty($param['having'])){
			$this->db->having($param['having']); 
		}
		if(isset($param['search']) && !empty($param['search'])){
			$this->db->where($param['search']);
			// $this->db->where('(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')');
		}
		if(isset($param['query']) && !empty($param['query'])){
			$this->db->where($param['query']);
			// $this->db->where('(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')');
		}
		if(isset($param['join']) && is_array($param['join']) && count($param['join'])){
			foreach ($param['join'] as $key => $value) {
				$this->db->join($value[0],$value[1],$value[2]);
			}
		}
		if(isset($param['distinct']) && !empty($param['distinct'])){
			$this->db->distinct();
		}
		if(isset($param['where']) && is_array($param['where']) && count($param['where'])){
			$this->db->where($param['where']);
		}
		if(isset($param['limit']) && $param['limit'] > 0){
			$this->db->limit($param['limit'], $param['start']);
		}
		if(isset($param['order_by']) && !empty($param['order_by'])){
			$this->db->order_by($param['order_by']);
		}
		if(isset($param['where_in']) && is_array($param['where_in']) && count($param['where_in']) && isset($param['where_in_field']) && $param['where_in_field'] != ''){
			$this->db->where_in($param['where_in_field'], $param['where_in']);
		}
		if(isset($param['where_not_in']) && is_array($param['where_not_in']) && count($param['where_not_in']) && isset($param['where_in_field']) && $param['where_in_field'] != ''){
			$this->db->where_not_in($param['where_in_field'], $param['where_not_in']);
		}
		if(isset($param['count']) && $param['count'] == TRUE){
			$result = $this->db->count_all_results();
		}else{
			if($flag == FALSE){
				$result = $this->db->get()->row_array();
			}else{
				$result = $this->db->get()->result_array();
			}
		}
		$this->db->flush_cache();
		return $result;
	}
	public function _update($param = ''){
		$this->db->where($param['where']);
		$this->db->update($param['table'], $param['data']);
		$result = $this->db->affected_rows(); // Sô dòng thay đổi trong database khi thực hiện câu update.
		$this->db->flush_cache();
		return $result;
	}
	public function _update_batch($param = ''){
		if(isset($param['update']) && is_array($param['update']) && count($param['update'])){
			$this->db->update_batch($param['table'], $param['update'],$param['field']);
		}		
		$result = $this->db->affected_rows(); // Số dòng thay đổi trong database.
		$this->db->flush_cache();
		return $result;
	}
	
	public function _create($param = ''){
		$this->db->insert($param['table'], $param['data']);
		$result = $this->db->affected_rows();
		if($result > 0){
			$result = $this->db->insert_id();
		} 
		$this->db->flush_cache();
		return $result;
		
	}
	public function _delete($param = ''){
		if(isset($param['where']) && is_array($param['where']) && count($param['where'])){
			$this->db->where($param['where']);
		}
		if(isset($param['where_in']) && is_array($param['where_in']) && count($param['where_in']) && isset($param['where_in_field']) && $param['where_in_field'] != ''){
			$this->db->where_in($param['where_in_field'], $param['where_in']);
		}
		$this->db->delete($param['table']);
		$result = $this->db->affected_rows(); // Sô dòng thay đổi trong database khi thực hiện câu update.
		$this->db->flush_cache();
		return $result;
	}
	
	public function _create_batch($param = ''){
		$this->db->insert_batch($param['table'], $param['data']);
		$result = $this->db->affected_rows();
		$this->db->flush_cache();
		return $result;
	}
	
	
	public function _condition($param = '', $catalogueid = ''){
		$result = '';
		$param['select'] = ((isset($param['select'])) ? $param['select'] : '');
		$param['limit'] = ((isset($param['limit'])) ? $param['limit'] : 5);
		$param['start'] = ((isset($param['start'])) ? $param['start'] : 0);
		$attribute['query'] = '';
		if(isset($param['where']) && $param['where'] != ''){
			$str_where = $param['where'];
		}
		if(isset($param['order_by']) && $param['order_by'] != ''){
			$order_by = $param['order_by'];
		}
		
		if(!isset($catalogueid) || is_array($catalogueid) == FALSE || count($catalogueid) == 0 ){
			if($param['catalogueid'] > 0){
				$catalogue = $this->_get_where(array(
					'select' => 'id, title, lft, rgt',
					'table' => $param['module'].'_catalogue',
					'where' => array('id' => $param['catalogueid']),
				));
				
				if($catalogue['rgt'] - $catalogue['lft'] > 1){
					$_list_child = $this->_get_where(array(
						'select' => 'id, title',
						'table' => $param['module'].'_catalogue',
						'where' => array('lft >=' => $catalogue['lft'],'rgt <=' => $catalogue['rgt']),
					), TRUE);
				}
				if(isset($_list_child) && is_array($_list_child) && count($_list_child)){
					foreach($_list_child as $key => $val){
						$attribute['query'] = $attribute['query'].(empty($attribute['query'])?('`att`.`catalogueid` = '.$val['id']):(' OR `att`.`catalogueid` = '.$val['id']));
					}
				}else{
					$attribute['query'] = $attribute['query'].(empty($attribute['query'])?('`att`.`catalogueid` = '.$catalogue['id']):(' OR `att`.`catalogueid` = '.$catalogue['id']));
				}
			}
		}else{
			foreach($catalogueid as $key => $val){
				$attribute['query'] = $attribute['query'].(empty($attribute['query'])?('`att`.`catalogueid` = '.$val['catalogueid']):(' OR `att`.`catalogueid` = '.$val['catalogueid']));
			}
		}
		$attribute['query'] = (!empty($attribute['query'])?' AND ('.$attribute['query'].')':'');
		if(isset($param['count']) && $param['count'] == TRUE){
			$result = $this->db->query('
				SELECT '.$param['select'].'
				FROM `catalogue_relationship` as `att`
				INNER JOIN `'.$param['module'].'` as `object`
				WHERE `object`.`publish` = 0 AND `att`.`module` = \''.$param['module'].'\' AND `att`.`moduleid` = `object`.`id`'.$attribute['query'].(!empty($str_where) ? ' AND '.$str_where:'').(!empty($param['keyword']) ? ' AND '.$param['keyword']:'').'
				GROUP BY `att`.`moduleid`
			')->num_rows();
		}else{

			$result = $this->db->query('
				SELECT '.$param['select'].'
				FROM `catalogue_relationship` as `att`
				INNER JOIN `'.$param['module'].'` as `object`
				WHERE `object`.`publish` = 0 AND `att`.`module` = \''.$param['module'].'\' AND `att`.`moduleid` = `object`.`id`'.$attribute['query'].(!empty($str_where) ? ' AND '.$str_where:'').(!empty($param['keyword']) ? ' AND '.$param['keyword']:'').'
				GROUP BY `att`.`moduleid`
				ORDER BY '.$param['order_by'].'
				LIMIT '.$param['start'].' , '.$param['limit'].'
			')->result_array();
		}
		
		$this->db->flush_cache();
		
		return $result;
	}
	
	
	
	

}
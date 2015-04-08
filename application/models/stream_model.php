<?php
class Stream_model extends MY_Model {
    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'stream_id';
    }
 
    /**
     * 表名称
     */
    public function tableName()
    {
        return 'stream';
    }
    
    function __construct()
    {
        parent::__construct();
    }
    
    //第一次记录信息
    function log_first ($data) {
    	
    	$request_data = get_post(NULL);
		$rs = array();
		$rs['order_bn'] = $data['order_bn'];
		$rs['from_method'] = $data['from_method'];
		$rs['to_method'] = $data['to_method'];
		$rs['to_sys'] = $data['to_sys'];
		$rs['from_sys'] = $data['from_sys'];
		$rs['response_data'] = json_encode($data['response_data']);
    	
		$rs['request_data'] = json_encode($request_data);
		$rs['createtime'] = time();
		$rs['last_modified'] = time();
		
		return parent::save($rs);
    }
    //第二次记录信息
    function log_second ($post_data,$stream_id) {
    //	var_dump($data);exit;
		$rs = array();
		$rs['return_data'] = json_encode($post_data);
		return parent::update($rs,array('stream_id'=>$stream_id));
    }
    
    
    
    
}
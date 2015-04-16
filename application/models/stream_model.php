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
    function log_first ($data,$form_certi,$to_certi) {
    	
    	$request_data = get_post(NULL);
		$rs = array();
		$rs['order_bn'] = $data['order_bn'];
		$rs['from_method'] = $data['from_method'];
		$rs['node_type'] = $data['node_type'];
		$rs['response_data'] = serialize($data['response_data']);
		$rs['request_data'] = serialize($request_data);
		$rs['createtime'] = time();
	//	$rs['callback_url'] = $data['callback_url'];
	//	$rs['callback_data'] = serialize($data['callback_data']);
		$rs['form_certi'] = $form_certi;
		$rs['to_certi'] = $to_certi;
		
		//$rs['last_modified'] = time();
		
		return parent::save($rs);
    }
    //第二次记录信息
    function log_second ($data,$stream_id) {
    //	var_dump($data);exit;
		$rs = array();
		$rs['return_data'] = $data['return_data'];
		$rs['callback_url'] = $data['callback_url'];
		$rs['callback_data'] = serialize($data['callback_data']);
		$rs['return_callback'] = '';
		$rs['msg_id'] = md5($stream_id);
		$rs['send_time'] = time();
		$rs['send_status'] = 1;
		if ($data['callback_url']) {
			$rs['callback_status'] = 0;
		} else {
			$rs['callback_status'] = 2;
		}
		
		return parent::update($rs,array('stream_id'=>$stream_id));
		
    }
    
    
    
    
    
}
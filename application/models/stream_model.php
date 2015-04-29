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
    function log_first ($data,$form_certi,$to_certi,$send_status) {
    	
    	$request_data = get_post(NULL);
		$rs = array();
		$rs['order_bn'] = $data['order_bn'];
		$rs['from_method'] = $data['from_method'];
		$rs['node_type'] = $data['node_type'];
		$rs['response_data'] = serialize($data['response_data']);
		$rs['request_data'] = serialize($request_data);
		$rs['createtime'] = time();
		$rs['form_certi'] = $form_certi;
		$rs['to_certi'] = $to_certi;
		$rs['send_status'] = $send_status;
		return parent::save($rs);
    }
    
    
    
    
    
    function log_sended ($return_data,$stream_id) {
    	$stream_rs = parent::findByAttributes(array('stream_id'=>$stream_id));
		$rs = array();
		$rs['return_data'] = $return_data;
		$rs['msg_id'] = md5($stream_id);
		$rs['send_time'] = time();
		if (empty($return_data) == false && $return_data != '-3' && $stream_rs['send_status'] == '0') {
			$rs['send_status'] = 2;
		}
		
//		$rs['retry'] = $stream_rs['retry']+1;
		//var_dump($rs);
		return parent::update($rs,array('stream_id'=>$stream_id));
    	
    }
    
    function log_callback ($callback_url,$callback_data,$return_callback,$stream_id) {
    //	var_dump($stream_id);
    	
    	$stream_rs = parent::findByAttributes(array('stream_id'=>$stream_id));
    	
		$rs = array();
		$rs['return_callback'] = $return_callback;
		$rs['callback_url'] = $callback_url;
		$rs['callback_data'] = empty($callback_data) ? '' : serialize($callback_data);
		
		if (empty($stream_rs['return_data']) == false && $stream_rs['return_data'] != '-3' && $stream_rs['send_status'] == '1' && empty($return_callback) == false && $return_callback != '-3') {
			$rs['send_status'] = 2;
		}
		
		
		 
	//	var_dump($rs);
		return parent::update($rs,array('stream_id'=>$stream_id));
    }
    
    function retry_lock($stream_id,$retry) {
    //	echo '-xx--';
		$rs = array();
		$rs['retry'] = $retry;
		$rs['retry_time'] = time();
		$rs['locked'] = 1;
		return parent::update($rs,array('stream_id'=>$stream_id));
    }
    
    function retry_unlock($stream_id) {
		$rs = array();
		$rs['locked'] = 0;
		return parent::update($rs,array('stream_id'=>$stream_id));
    }
    
    
    //记录发送后的数据
    function log_send_all ($data,$stream_id) {
    	$stream_rs = parent::findByAttributes(array('stream_id'=>$stream_id));
    	
    	
		$rs = array();
		
		$rs['return_data'] = $data['return_data'];
		$rs['callback_url'] = $data['callback_url'];
		$rs['callback_data'] = empty($data['callback_data']) ? '' : serialize($data['callback_data']);
		$rs['return_callback'] = $data['return_callback'];
		
		$rs['msg_id'] = md5($stream_id);
		$rs['send_time'] = time();
		
		if (empty($data['return_data']) == false && $data['return_data'] != '-3' && $stream_rs['send_status'] == '0') {
			$rs['send_status'] = 2;
		} else if (empty($data['return_data']) == false && $data['return_data'] != '-3' && empty($data['callback_data']) == false && $data['callback_data'] != '-3' && $stream_rs['send_status'] == '1') {
			$rs['send_status'] = 2;
		}
		
		$rs['retry'] = 1;
		
		return parent::update($rs,array('stream_id'=>$stream_id));
		
    }
    
    
    
    
    
    
}
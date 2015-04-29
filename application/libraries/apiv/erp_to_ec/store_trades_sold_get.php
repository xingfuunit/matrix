<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Store_trades_sold_get {
	var $right_away = FALSE;
	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
	
    
    function _init() {
    	
    	$request_data = get_post(NULL);
    	$response_data = array();
    	
    	
    	$CI =& get_instance();
    	
    	$certi = get_post('matrix_certi',true);
    	//获得发送到ec url
    	$CI->load->model('certi_model');
    	$certi_rs = $CI->certi_model->findByAttributes(array('certi_name'=>$certi));
    	
    	
    	$response_data['to_node_id'] = $request_data['to_node_id'];
    	$response_data['rights_level'] = "custom";
    	$response_data['refresh_time'] = date('Y-m-d H:i:s',time());   //只有接收端有，所以直接用格式时间
    	$response_data['to_api_v'] = $request_data['to_api_v'];
    	$response_data['format'] = $request_data['format'];
    	$response_data['timestamp'] = time();					//无法理解，传个时间入去试试
    	$response_data['start_time'] = $request_data['start_time'];
    	$response_data['msg_id'] = true;
    	$response_data['from_api_v'] = $request_data['from_api_v'];
    	$response_data['app_id'] = 'ecos.b2c';
    	$response_data['node_id'] = $request_data['from_node_id'];
    	$response_data['certi_id'] = $request_data['certi_id'];
    	$response_data['date'] = $request_data['date'];
    	$response_data['from_node_id'] = $request_data['from_node_id'];
    	
    	//此task 奇怪 处理端ec 会有，但发送端erp没有,   随便生成一个试试 
    	$response_data['task'] = md5($response_data['date']);
    	$response_data['from_token'] = $CI->config->item('erp_token');
    	
    	$response_data['api_url'] = $certi_rs['api_url'];
//     	$response_data['api_url'] = 'http://lwq.pinzhen365.com/api';
    	$response_data['channel_ver'] = "";
    	$response_data['fields'] = $request_data['fields'];
    	$response_data['from_type'] = $request_data['app_id'];
    	$response_data['page_size'] = $request_data['page_size'];
    	$response_data['end_time'] = $request_data['end_time'];
    	$response_data['v'] = $request_data['v'];
    	$response_data['_id'] = "rel_".$request_data['from_node_id']."_store.trades.sold.get_".$request_data['to_node_id'];
    	$response_data['method'] = "b2c.order.search";
    	$response_data['channel'] = "";
    	$response_data['to_token'] = $CI->config->item('ec_token');
    	
    	return array(
    			'response_data'=>$response_data,
    			'from_method'=>$request_data['method'],
    			'node_type'=>$request_data['node_type'],
    			'is_callback'=>false);
    //	$CI->load->library('common/httpclient');
    	
    }
    
    
    function result($post_data) {
    	
    	$return_data = json_decode($post_data['return_data']);
    	$return_data = object_array($return_data);
    	
    	file_put_contents('matrix_juzhen.log', date("Y-m-d H:i:s",time()).' matrix_store_trades_result:'.print_r($post_data,1)."\r\n",FILE_APPEND);
    	
    	if($return_data['rsp'] ==  'succ'){
    		 $re = array(
    					'res' 			=> '',
    					'msg_id' 		=> $post_data['msg_id'],
    					'err_msg'		=> '',
    					'data'			=> $return_data['data'],
    					'rsp'			=> 'succ',
    					'res_ltype'		=> $return_data['data']['total_results'],
    				);
    		 
    		 file_put_contents('matrix_juzhen.log', date("Y-m-d H:i:s",time()).' matrix_store_trades_re:'.print_r($re,1)."\r\n",FILE_APPEND);
    		 
    		 return json_encode($re);
    	}else{
    		return '{"res": "", "msg_id": "'.$post_data['msg_id'].'", "rsp": "fail", "err_msg": "", "data": ""}';
    	}

	
    	
    }
    
    
}

?>
<?php
class Certi_model extends MY_Model {
    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'certi_id';
    }
 
    /**
     * 表名称
     */
    public function tableName()
    {
        return 'certi';
    }
    
    function __construct()
    {
        parent::__construct();
    }
    
    //验证请求信息
    function check($from_node_id,$timestamp,$token,$to_node_id) {
    	
    	$rs = parent::findByAttributes(array('node_id'=>$from_node_id));
    	if ($rs) {
    		$md5_key = md5($rs['certi_name'].$rs['certi_key'].$timestamp);
    		if ($token == $md5_key) {
    			$to_rs = parent::findByAttributes(array('node_id'=>$to_node_id));
    			
    			$to_rs['library_type'] = '';
    			if ($rs['certi_type'] == 'erp' && $to_rs['certi_type'] == 'ecstore') {
    				$to_rs['library_type'] = 'shopex';
    			}  else if ($rs['certi_type'] == 'ecstore' && $to_rs['certi_type'] == 'erp') {
    				$to_rs['library_type'] = 'shopex';
    			} else if ($rs['certi_type'] == 'youzan' && $to_rs['certi_type'] == 'erp') {
    				$to_rs['library_type'] = 'youzan';
    			}  else if ($rs['certi_type'] == 'erp' && $to_rs['certi_type'] == 'youzan') {
    				$to_rs['library_type'] = 'youzan';
    			} 
    			
    			return $to_rs;
    		}
    	} 
    	return false;
    }
}
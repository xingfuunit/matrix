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
    function check($certi,$timestamp,$token,$to_certi) {
    	
    	$rs = parent::findByAttributes(array('certi_name'=>$certi));
    	if ($rs) {
    		$md5_key = md5($rs['certi_name'].$rs['certi_key'].$timestamp);
    		if ($token == $md5_key) {
    			$to_rs = parent::findByAttributes(array('certi_name'=>$to_certi));
    			return $to_rs['api_url'];
    		}
    	} 
    	return false;
    }
}
 
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Model extends CI_Model
{
    /**
     * ��������
     * 
     * @param array $data ��Ҫ����ı�����
     * @return boolean ����ɹ�����ID������ʧ�ܷ���false
     */
    public function save($data)
    {
        if($this->db->set($data)->insert($this->tableName())) {
            return $this->db->insert_id();
        }
        return FALSE;
    }
 
    /**
     * Replace����
     * @param array $data
     */
    public function replace($data)
    {
        return $this->db->replace($this->tableName(), $data);
    }
 
    /**
     * �����������¼�¼
     * 
     * @param string $pk ����ֵ
     * @param array $attributes �����ֶ�
     * @param array $where ����where����
     * @return boolean true���³ɹ� false����ʧ��
     */
    public function updateByPk($pk, $attributes, $where = array())
    {
        $where[$this->primaryKey()] = $pk;
        return $this->updateAll($attributes, $where);
    }
 
    /**
     * ���±��¼
     * 
     * @param array $attributes
     * @param array $where
     * @return bollean true���³ɹ� false����ʧ��
     */
    public function update($attributes, $where = array())
    {
        return $this->db->where($where)->update($this->tableName(), $attributes);
    }
 
    /**
     * ��������ɾ������
     * 
     * @param string $pk ����ֵ
     * @param array $where ����ɾ������
     * @return boolean trueɾ���ɹ� falseɾ��ʧ�� 
     */
    public function deleteByPk($pk, $where = array())
    {
        $where[$this->primaryKey()] = $pk;
        return $this->deleteAll($where);
    }
 
    /**
     * ɾ����¼
     * 
     * @param array $where ɾ������
     * @param int $limit ɾ������
     * @return boolean trueɾ���ɹ� falseɾ��ʧ��
     */
    public function deleteAll($where = array(), $limit = NULL)
    {
        return $this->db->delete($this->tableName(), $where, $limit);
    }
 
    /**
     * ������������
     * 
     * @param string $pk
     * @param array $where ���Ӳ�ѯ����
     * @return array ����һά���飬δ�ҵ���¼�򷵻ؿ�����
     */
    public function findByPk($pk, $where = array())
    {
        $where[$this->primaryKey()] = $pk;
        $query = $this->db->from($this->tableName())->where($where)->get();
        return $query->row_array();
    }
 
    /**
     * �������Ի�ȡһ�м�¼
     * @param array $where
     * @return array ����һά���飬δ�ҵ���¼�򷵻ؿ�����
     */
    public function findByAttributes($where = array(),$sort = NULL)
    {
        $this->db->from($this->tableName())->where($where);
        if($sort !== NULL) {
            if(is_array($sort)){
                foreach($sort as $value){
                    $this->db->order_by($value, '', false);
                }
            } else {
                $this->db->order_by($sort);
            }
        }
        $query = $this->db->limit(1)->get();
        return $query->row_array();
    }
 
    /**
     * ��ѯ��¼
     * 
     * @param array $where ��ѯ��������ʹ��ģ����ѯ����array('name LIKE' => "pp%") array('stat >' => '1')
     * @param int $limit ���ؼ�¼����
     * @param int $offset ƫ����
     * @param string|array $sort ����, ��Ϊ�����ʱ�� �磺array('id DESC', 'report_date ASC')����ͨ���ڶ��������������Ƿ�escape
     * @return array δ�ҵ���¼���ؿ�����
     */
    public function findAll($where = array(), $limit = 0, $offset = 0, $sort = NULL)
    {
        $this->db->from($this->tableName())->where($where);
        if($sort !== NULL) {
            if(is_array($sort)){
                foreach($sort as $value){
                    $this->db->order_by($value, '', false);
                }
            } else {
                $this->db->order_by($sort);
            }
        }
        if($limit > 0) {
            $this->db->limit($limit, $offset);
        }
 
        $query = $this->db->get();
 
        return $query->result_array();
    }
 
    /**
     * ͳ����������������
     * 
     * @param array $where ͳ������
     * @return int ���ؼ�¼����
     */
    public function count($where = array())
    {
        return $this->db->from($this->tableName())->where($where)->count_all_results();
    }
 
    /**
     * ����SQL��ѯ�� ����ͨ��$param��
     * @param string $sql ��ѯ��䣬��SELECT * FROM some_table WHERE id = ? AND status = ? AND author = ?
     * @param array $param array(3, 'live', 'Rick')
     * @return array δ�ҵ���¼���ؿ����飬�ҵ���¼���ض�ά����
     */
    public function query($sql, $param = array())
    {
        $query = $this->db->query($sql, $param);
        return $query->result_array();
    }
}
 
/* End of file ActiveRecord.php */
/* Location: ./application/core/database/ActiveRecord.php */
 
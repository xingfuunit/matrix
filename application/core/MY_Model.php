 
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Model extends CI_Model
{
    /**
     * 保存数据
     * 
     * @param array $data 需要插入的表数据
     * @return boolean 插入成功返回ID，插入失败返回false
     */
    public function save($data)
    {
        if($this->db->set($data)->insert($this->tableName())) {
            return $this->db->insert_id();
        }
        return FALSE;
    }
 
    /**
     * Replace数据
     * @param array $data
     */
    public function replace($data)
    {
        return $this->db->replace($this->tableName(), $data);
    }
 
    /**
     * 根据主键更新记录
     * 
     * @param string $pk 主键值
     * @param array $attributes 更新字段
     * @param array $where 附加where条件
     * @return boolean true更新成功 false更新失败
     */
    public function updateByPk($pk, $attributes, $where = array())
    {
        $where[$this->primaryKey()] = $pk;
        return $this->updateAll($attributes, $where);
    }
 
    /**
     * 更新表记录
     * 
     * @param array $attributes
     * @param array $where
     * @return bollean true更新成功 false更新失败
     */
    public function update($attributes, $where = array())
    {
        return $this->db->where($where)->update($this->tableName(), $attributes);
    }
 
    /**
     * 根据主键删除数据
     * 
     * @param string $pk 主键值
     * @param array $where 附加删除条件
     * @return boolean true删除成功 false删除失败 
     */
    public function deleteByPk($pk, $where = array())
    {
        $where[$this->primaryKey()] = $pk;
        return $this->deleteAll($where);
    }
 
    /**
     * 删除记录
     * 
     * @param array $where 删除条件
     * @param int $limit 删除行数
     * @return boolean true删除成功 false删除失败
     */
    public function deleteAll($where = array(), $limit = NULL)
    {
        return $this->db->delete($this->tableName(), $where, $limit);
    }
 
    /**
     * 根据主键检索
     * 
     * @param string $pk
     * @param array $where 附加查询条件
     * @return array 返回一维数组，未找到记录则返回空数组
     */
    public function findByPk($pk, $where = array())
    {
        $where[$this->primaryKey()] = $pk;
        $query = $this->db->from($this->tableName())->where($where)->get();
        return $query->row_array();
    }
 
    /**
     * 根据属性获取一行记录
     * @param array $where
     * @return array 返回一维数组，未找到记录则返回空数组
     */
    public function findByAttributes($where = array())
    {
        $query = $this->db->from($this->tableName())->where($where)->limit(1)->get();
        return $query->row_array();
    }
 
    /**
     * 查询记录
     * 
     * @param array $where 查询条件，可使用模糊查询，如array('name LIKE' => "pp%") array('stat >' => '1')
     * @param int $limit 返回记录条数
     * @param int $offset 偏移量
     * @param string|array $sort 排序, 当为数组的时候 如：array('id DESC', 'report_date ASC')可以通过第二个参数来控制是否escape
     * @return array 未找到记录返回空数组
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
     * 统计满足条件的总数
     * 
     * @param array $where 统计条件
     * @return int 返回记录条数
     */
    public function count($where = array())
    {
        return $this->db->from($this->tableName())->where($where)->count_all_results();
    }
 
    /**
     * 根据SQL查询， 参数通过$param绑定
     * @param string $sql 查询语句，如SELECT * FROM some_table WHERE id = ? AND status = ? AND author = ?
     * @param array $param array(3, 'live', 'Rick')
     * @return array 未找到记录返回空数组，找到记录返回二维数组
     */
    public function query($sql, $param = array())
    {
        $query = $this->db->query($sql, $param);
        return $query->result_array();
    }
}
 
/* End of file ActiveRecord.php */
/* Location: ./application/core/database/ActiveRecord.php */
 
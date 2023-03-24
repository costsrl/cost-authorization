<?php
namespace CostAuthorization\Model\TableGateway;
use CostBase\Model\TableGateway\BasicTableGateway as BasicTableGateway;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\Sql\Expression;
use Laminas\Paginator\Paginator;
use Laminas\Db\ResultSet\ResultSet;

class Permission extends BasicTableGateway
{
    /**
     *
     * @param string $where
     * @return Ambigous <\Laminas\Db\Sql\Select, \Laminas\Db\Sql\Select>
     */
    public function getDefaultSql($where=null)
    {
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select()
        ->from(array('pr'=>$this->table))
        ->columns(array('id', 'resource_id', 'role_id', 'name','privilege' ,'permission_allow','assert_class'))
        ->join(array('r'=>$this->getProxitable('roles')),'pr.role_id = r.id', array('role_name'=>'name'),'INNER')
        ->join(array('rs'=>$this->getProxitable('resources')),'pr.resource_id = rs.id', array('resource_name'=>'name'),'INNER');
        return $select;
    }
    
    
    /** ESTRAZIONI CON COMBO **/
    public function fetchPairs($flag=true){
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select();
        $select->from($this->table);
        $select->columns(array('id',new \Laminas\Db\Sql\Expression('CONCAT(privilege,\':\',name)')))->order('id ASC');
        $result = $this->selectWith($select)->toArray();
        $rs=$this->selectWith($select)->toArray();
        if($flag) $return['']= '';
        foreach ($rs as $row) {
            $row = array_values($row);
            $return[trim($row[0])] = trim($row[1]);
        }
    
        return $return;
    }
    
    /**
     * @return string
     */
    protected function generateKey(){
        return md5($this->table);
    }
    
    
    /**
     * (non-PHPdoc)
     * @see \Laminas\Db\TableGateway\AbstractTableGateway::__call()
     */
    public function __call($method, $arguments){
        $this->table->$method($arguments);
    }
    
}

?>
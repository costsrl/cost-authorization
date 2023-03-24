<?php
namespace CostAuthorization\Model\TableGateway;

use CostBase\Model\TableGateway\BasicTableGateway as BasicTableGateway;
use Laminas\Db\Sql;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\Sql\Expression;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Adapter\Iterator;
use Laminas\Paginator\Adapter\ArrayAdapter;
use Laminas\Paginator\Paginator;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\Feature\GlobalAdapterFeature;
use Laminas\Db\TableGateway\Feature\EventFeature;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\Db\TableGateway\AbstractTableGateway;


class Role extends BasicTableGateway
{
    
    /**
     *
     * @param string $where
     * @return  \Laminas\Db\Sql\Select
     */
    public function getDefaultSql($where=null)
    {
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select()
        ->from(array('r'=>$this->table))
        ->columns(array('id','name'))
        ->join(array('r1'=>$this->getProxitable('roles')),'r1.id = r.parent_id',array('parent'=>'name','parent_id' ),'LEFT');
        $select->order('r.parent_id ASC');
        //echo $sql->getSqlStringForSqlObject($select);
        //die();
        return $select;
    }


    public function getRoles($where=null)
    {
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select()
            ->columns(array('id' ,'name', 'parent_id',new Expression('CONCAT(id,"_",name) AS name_id')))
            ->from(array('r'=>$this->table))
            ->columns(array('id','name'))
            ->join(array('r1'=>$this->getProxitable('roles')),'r1.id = r.parent_id',array('parent'=>'name','parent_id' ),'LEFT');
            //$select->order('r.parent_id ASC');
        //echo $sql->getSqlStringForSqlObject($select);
        return $select;
    }
    
    
    /** ESTRAZIONI CON COMBO **/
    public function fetchPairs($flag=true){
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select();
        $select->from($this->table);
        $select->columns(array('id','name'))->order('id ASC, parent_id ASC');
        $result = $this->selectWith($select)->toArray();
        $rs=$this->selectWith($select)->toArray();
        if($flag) $return['']= '';
        foreach ($rs as $row) {
            $row = array_values($row);
            $return[trim($row[0])] = trim($row[1]);
        }
    
        return $return;
    }
    
    
    public function fetchPairsByName($flag=true){
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select();
        $select->from($this->table);
        $select->columns(array('name'))->order('id ASC, parent_id ASC');
        $result = $this->selectWith($select)->toArray();
        $rs=$this->selectWith($select)->toArray();
        if($flag) $return['']= '';
        foreach ($rs as $row) {
            $row = array_values($row);
            $return[trim($row[0])] = trim($row[0]);
        }
    
        return $return;
    }
    
    
    public function getRoleNameById($id=0){
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select();
        $select->from($this->table);
        $select->columns(array('name'));
        
        $where = new Where();
        $where->equalTo('id', $id);
        $select->where($where);
        $select->order('id ASC, parent_id ASC');
        //echo $sql->getSqlStringForSqlObject($select);
        $result = $this->selectWith($select)->toArray();
        var_dump($result);
        return $result[0]['name'];
        
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
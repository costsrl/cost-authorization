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

class Resource extends BasicTableGateway
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
        ->from(array('rs'=>$this->table))
        ->columns(array('id','name'));
        return $select;
    }


    /** ESTRAZIONI CON COMBO **/
    public function fetchPairs($flag=true){
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select();
        $select->from($this->table);
        $select->columns(array('id','name'))->order('id ASC');
        $result = $this->selectWith($select)->toArray();
        $rs=$this->selectWith($select)->toArray();
        if($flag) $return['']= '';
        foreach ($rs as $row) {
            $row = array_values($row);
            $return[trim($row[0])] = trim($row[1]);
        }

        return $return;
    }


    public function fetchGridPairs($flag=true){
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select();
        $select->from($this->table);
        $select->columns(array('id','name'))->order('id ASC');
        $result = $this->selectWith($select)->toArray();
        $rs=$this->selectWith($select)->toArray();
        if($flag) $return['']= '';
        foreach ($rs as $row) {
            $row = array_values($row);
            $return[trim($row[1])] = trim($row[1]);
        }

        return $return;
    }


    /** ESTRAZIONI CON COMBO **/
    public function fetchPairsMenu($flag=true){
        $sql= new \Laminas\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select();
        $select->from($this->table);
        $select->where(array('type'=>'controller'));
        $select->columns(array('id','name'))->order('id ASC');
        $result = $this->selectWith($select)->toArray();
        $rs=$this->selectWith($select)->toArray();
        if($flag) $return['']= '';
        foreach ($rs as $row) {
            $row = array_values($row);
            $return[trim($row[1])] = trim($row[1]);
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
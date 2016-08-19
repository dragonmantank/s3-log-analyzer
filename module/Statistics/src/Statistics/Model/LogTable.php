<?php
namespace Statistics\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class LogTable
{
    protected $tableGateway;

    /**
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->tableGateway = new TableGateway('logs', $adapter);
    }
    
    /**
     * @param $id
     * @return array
     */
    public function find($id)
    {
        $resultSet = $this->tableGateway->select(array('id' => $id));

        return $resultSet->toArray();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet->toArray();
    }
    
    public function totalRequestsByDay()
    {
        $resultSet = $this->tableGateway->select(function (Select $select)
        {
            $select->columns(['date', 'downloads' => new Expression('COUNT(id)') ]);
            $select->group('date');
        });
    
        return $resultSet->toArray();
    }
    
    public function totalRequestsByFile()
    {
        $resultSet = $this->tableGateway->select(function (Select $select)
        {
            $select->columns(['file', 'downloads' => new Expression('COUNT(id)') ]);
            $select->group(['file']);
            $select->order(['file' => 'DESC']);
        });
        
        return $resultSet->toArray();
    }
    
    public function totalRequestsByFileUnique()
    {
        $resultSet = $this->tableGateway->select(function (Select $select)
        {
            $select->columns(['file', 'downloads' => new Expression('COUNT(DISTINCT(ip))') ]);
            $select->group(['file']);
            $select->order(['file' => 'DESC']);
        });
        
        return $resultSet->toArray();
    }
    
    public function totalRequestsByFileByDay($filename)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($filename)
        {
            $select->columns(['date', 'downloads' => new Expression('COUNT(id)') ]);
            $select->where(['file' => $filename]);
            $select->group(['date']);
        });
        
        return $resultSet->toArray();
    }
    
    public function totalRequestsByFileUniqueByDay($filename)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($filename)
        {
            $select->columns(['date', 'unique_downloads' => new Expression('COUNT(DISTINCT(ip))') ]);
            $select->where(['file' => $filename]);
            $select->group(['date']);
        });
        
        return $resultSet->toArray();
    }
}

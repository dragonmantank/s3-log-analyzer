<?php
namespace Logs\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

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
    
    public function insert($data)
    {
        $result = $this->tableGateway->insert($data);
        
        return $result;
    }
}

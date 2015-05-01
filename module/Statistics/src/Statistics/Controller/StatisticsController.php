<?php
namespace Statistics\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Statistics\Model\LogTable;

class StatisticsController extends AbstractActionController
{
    private $logTable;
    
    public function __construct(LogTable $logTable)
    {
        $this->logTable = $logTable;
    }
    
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function dailytotalsAction()
    {
        $logs = $this->logTable->totalRequestsByDay();
        $files = $this->logTable->totalRequestsByFile();
        $filesUnique = $this->logTable->totalRequestsByFileUnique();
        
        return new ViewModel(['logs' => $logs, 'files' => $files, 'filesunique' => $filesUnique]);
    }
    
    public function filetotalsAction()
    {
        $filename = $this->getEvent()->getRouteMatch()->getParam('filename');
        
        // @todo need to combine the next two arrays, separately is dirty
        $logs = $this->logTable->totalRequestsByFileByDay(mysql_real_escape_string($filename));
        $logsUnique = $this->logTable->totalRequestsByFileUniqueByDay(mysql_real_escape_string($filename));
        
        return new ViewModel(['logs' => $logs, 'filename' => $filename, 'logsUnique' => $logsUnique]);
    }
}

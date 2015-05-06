<?php
namespace Logs\Controller;

use League\Flysystem\FilesystemInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Logs\Model\LogTable;

class LogsController extends AbstractActionController
{
    private $logTable;
    
    public function __construct(LogTable $logTable)
    {
        $this->logTable = $logTable;
    }
    
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        // @todo this should be an array from the DB with buckets
        $buckets = ['default'];
        
        return new ViewModel(['buckets' => $buckets]);
    }
    
    /**
     * @return ViewModel
     */
    public function processAction()
    {
        $bucket = $this->getEvent()->getRouteMatch()->getParam('bucket');
        
        $i = 0;
        $sm = $this->getServiceLocator();
        $filesystem = $sm->get('BsbFlysystemManager')->get($bucket);
        
        $logs = $this->getLoglist($filesystem);
        
        // Get each log file for parsing
        foreach ($logs as $log) {
            
            $logEntries = $this->parseLog($filesystem->read($log['path']));
            
            // add entries to DB
            if ($this->insertLogToDb($logEntries)) {
                $filesystem->delete($log['path']);
            }
            
            $i++;
        }
        
        return new ViewModel(['contents' => "success", "processed" => $i]);
    }
    
    /**
     * @param $filesystem
     * @return mixed
     */
    private function getLoglist(FilesystemInterface $filesystem)
    {
        return $filesystem->listContents('log/');
    }
    
    /**
     * @param $log
     * @return array
     */
    private function parseLog($log)
    {
        $logLines = [];
        $rows = explode("\n", $log);
        
        foreach ($rows as $row) {
            $pattern = '/(?P<owner>\S+) (?P<bucket>\S+) (?P<time>\[[^]]*\]) (?P<ip>\S+) (?P<requester>\S+) (?P<reqid>\S+) (?P<operation>\S+) (?P<key>\S+) (?P<request>"[^"]*") (?P<status>\S+) (?P<error>\S+) (?P<bytes>\S+) (?P<size>\S+) (?P<totaltime>\S+) (?P<turnaround>\S+) (?P<referrer>"[^"]*") (?P<useragent>"[^"]*") (?P<version>\S)/';
    
            preg_match($pattern, $row, $matches);
    
            if (!empty($matches)) {
                $logLines[] = $matches;
            }
        }
        
        return $logLines;
    }
    
    /**
     * @param $logEntries
     * @return boolean
     */
    private function insertLogToDb($logEntries)
    {
        $today = date('Y-m-d H:i:s');
        $result = true;
        
        // insert the successful request from the log into DB
        foreach ($logEntries as $logEntry) {
            
            if ($logEntry['status'] >= 200 && $logEntry['status'] < 300) {
                $dateTime = $this->cleanDate($logEntry['time']);
    
                $record['bucket'] = $logEntry['bucket'];
                $record['date'] = date('Y-m-d', $dateTime);
                $record['time'] = date('H:i:s', $dateTime);
                $record['datetime'] = date('Y-m-d H:i:s', $dateTime);
                $record['ip'] = $logEntry['ip'];
                $record['file'] = $logEntry['key'];
                $record['useragent'] = $logEntry['useragent'];
                $record['created'] = $today;
    
                if (!$this->logTable->insert($record)) {
                    $result = false;
                }
                
                break;
            }
        }
        
        return $result;
    }
    
    /**
     * Takes AWS S3 timestamp and converts to an EST desired format for DB insert.
     * 
     * [17/Apr/2015:05:07:46 +0000] -> 2015-04-17 05:07:46
     * 
     * NOTE: This is totally geared toward EST, so needs to be more timezone friendly
     * in the future.
     * 
     * @param $AwsDateTime
     * @return bool|string
     */
    private function cleanDate($AwsDateTime)
    {
        $dateTime = strtotime(str_replace(['[',']'],'',$AwsDateTime) . ' + 5 hours');
        return $dateTime;
    }
}

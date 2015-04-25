<?php
namespace Logs\Model;

class Log
{
    public $id;
    public $bucket;
    public $time;
    public $ip;
    public $file;
    public $useragent;
    public $created;

    /**
     * @param $data
     */
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->bucket = (isset($data['bucket'])) ? $data['bucket'] : null;
        $this->time  = (isset($data['time']))  ? $data['time']  : null;
        $this->ip  = (isset($data['ip']))  ? $data['ip']  : null;
        $this->file  = (isset($data['file']))  ? $data['file']  : null;
        $this->useragent  = (isset($data['useragent']))  ? $data['useragent']  : null;
        $this->created  = (isset($data['created']))  ? $data['created']  : null;
    }
}

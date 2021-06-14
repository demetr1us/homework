<?php


namespace App\Models;


class FileGenerator
{
    protected $filename;
    protected $tasklist=[];
    protected $added=[];
    protected $result=[];


    public function __construct($json)
    {
        $this->filename = $json->get('filename');
        $this->tasklist = $json->get('commands');
    }


    function canBeAdded($task){
        return empty(array_diff($task['deps'], $this->added));
    }

    function add($task){
        array_push($this->added, $task['name']);
        array_push($this->result, $task['command']);
    }

    function generateFileFromJson(){
        $added = true;
        while (!empty($this->tasklist) && $added) {
            $added = false;
            foreach ($this->tasklist as $key => $command) {
                if ($this->canBeAdded($command)) {
                    $this->add($command);
                    $added = true;
                    unset($this->tasklist[$key]);
                }
            }
        }
        file_put_contents($this->filename,implode("\n", $this->result));
    }
}

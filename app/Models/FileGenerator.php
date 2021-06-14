<?php


namespace App\Models;


class FileGenerator
{
    protected string $filename;
    protected array $tasklist=[];
    protected array $added=[];
    protected array $result=[];


    public function __construct($json)
    {
        $this->filename = $json->get('filename');
        $this->tasklist = $json->get('commands');
    }


    function canBeAdded($task):bool{
        return empty(array_diff($task['deps'], $this->added));
    }

    function add($task){
        array_push($this->added, $task['name']);
        array_push($this->result, $task['command']);
    }

    public function getNotAdded():array{
        $result = [];
        foreach ($this->tasklist as $task){
            array_push($result, $task['name']);
        }
        return $result;
    }

    function generateFileFromJson():bool{
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
        if (empty($this->tasklist)){
            file_put_contents($this->filename,implode("\n", $this->result));
            return true;
        }
        return false;
    }
}

<?php
class installer{
  public $repositories = array();
  public $root;

  public function __contruct(){
    
  }

  public function add_repo($origin, $path = ''){
    $this->repositories[__DIR__.'/mdl/'.$path] = $origin;
  }

  public function exec(){
    foreach ($this->repositories as $path => $origin){
      if ($path !== __DIR__ && !file_exists($path)) {
         $clone = $this->git('clone');  
         $clone($origin.' '.$path);    
      }
      else if ($path !== __DIR__) {
         shell_exec('cd '.$path);
         $fetch = $this->git('fetch');
         $fetch('origin');

         $merge = $this->git('merge');
         $merge('origin/master');
      }
      else {
        $remote = $this->git('remote');
        $r = $remote('-v');
        var_dump($r);
        if ($r == ''){
            $clone = $this->git('clone');  
            $clone('-b MOODLE_28_STABLE '.$origin.' '.$path);
        }
      }
    }
  }

  public function git($command){
    return function($param) use ($command) {
        $gitDo = 'git '.$command.' '.$param;
        // echo $gitDo; 
        shell_exec($gitDo);            
    };
  }


}

$test = new installer();
$test->add_repo('https://github.com/moodle/moodle.git');
$test->exec();


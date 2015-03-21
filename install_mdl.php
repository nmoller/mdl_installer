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
      if (!file_exists($path) && $path !== __DIR__.'/mdl/') {
         $clone = $this->git('clone');  
         $clone($origin.' '.$path);    
      }
      else if (!file_exists($path)){
          $clone = $this->git('clone');  
          $clone('-b MOODLE_28_STABLE '.$origin.' '.$path);
          /*
         shell_exec('cd '.$path);
         $remote = $this->git('remote');
         $r = $remote('-v');
         if ($r == ''){
            $clone = $this->git('clone');  
            $clone('-b MOODLE_28_STABLE '.$origin.' '.$path);
         }
        */
      }
      else {
         shell_exec('cd '.$path);
         $fetch = $this->git('fetch');
         $fetch('origin');

         $merge = $this->git('merge');
         $merge('origin/master');
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
$test->add_repo('https://github.com/nmoller/local_scripts.git','local/scripts');
$test->exec();


<?php
class installer{
  public $repositories = array();
  public $root;
  public $debug = false;

  public function __construct($root = 'mdl', $follow = 'MOODLE_28_STABLE'){
    $this->follows = $follow;
    $this->root = __DIR__.'/'.$root;

  }

  public function add_repo($origin, $path = ''){
    $this->repositories[$this->root.'/'.$path] = $origin;
  }

  public function debug(){
    $this->debug = true;
  }

  public function exec(){
    foreach ($this->repositories as $path => $origin){
      if (!file_exists($path) && $path !== $this->root) {
         $this->message("Clonning");
         $clone = $this->git('clone');  
         $clone($origin.' '.$path);    
      }
      else if (!file_exists($path)){
          $clone = $this->git('clone');  
          $clone('-b '.$this->follows.' '.$origin.' '.$path);
          $this->message("MDL-created");
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
          $this->message($path);
         $fetch = $this->git('fetch');
         $fetch('origin');

         $merge = $this->git('merge');
         $merge('origin/master');
         $this->message("Updating");
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

  public function message($text){
    if ($this->debug) echo $text.PHP_EOL;
  }


}

$test = new installer();
$test->debug();
$test->add_repo('https://github.com/moodle/moodle.git');
$test->add_repo('https://github.com/nmoller/local_scripts.git','local/scripts');
$test->exec();


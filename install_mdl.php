<?php
class Installer{
  public $repositories = array();
  public $root;
  public $debug = false;

  public function __construct($root = 'mdl', $follow = 'MOODLE_28_STABLE'){
    $this->follows = $follow;
    $this->root = __DIR__.'/'.$root;

  }

  public function addRepo($origin, $path = ''){
    $this->repositories[$this->root.'/'.$path] = $origin;
  }

  public function debug(){
    $this->debug = true;
  }

  public function exec(){
    foreach ($this->repositories as $path => $origin){
      if (!file_exists($path) && $path !== $this->root.'/') {
         $this->message("Clonning");
         $clone = $this->git('clone');  
         $clone($origin.' '.$path);    
      }
      else if (!file_exists($path)){
          $clone = $this->git('clone');  
          $clone('-b '.$this->follows.' --single-branch '.$origin.' '.$path);
          $this->message("MDL-created");
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
        if ($this->debug) echo $gitDo.PHP_EOL; 
        else shell_exec($gitDo);            
    };
  }

  public function message($text){
    if ($this->debug) echo $text.PHP_EOL;
  }


}

$test = new Installer('test_ins');
$test->addRepo('https://github.com/moodle/moodle.git');
$test->addRepo('https://github.com/mgage/wwlink.git','blocks/wwlink');
$test->addRepo('https://github.com/mgage/wwassignment.git','mod/wwassignment');

$test->exec();


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
      // echo $path.PHP_EOL;
      // echo $this->root.PHP_EOL;
      if (!file_exists($path) && $path !== $this->root.'/') {
         $this->message("Clonning");
         $clone = $this->git('clone');  
         $clone($origin.' '.$path);    
      }
      else if (!file_exists($path)){
          /*
          $init = $this->git('init');
          $init = $init('');
          $fetch = $this->git('fetch');
          $fetch($origin.' '.$this->follows.':refs/remotes/origin/'.$this->follows);
          */
         
          $clone = $this->git('clone');  
          $clone('-b '.$this->follows.' --single-branch '.$origin.' '.$path);
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
        if ($this->debug) echo $gitDo.PHP_EOL; 
        else shell_exec($gitDo);            
    };
  }

  public function message($text){
    if ($this->debug) echo $text.PHP_EOL;
  }


}

$test = new installer('test_ins', 'cad28');
// $test->debug();
$test->add_repo('ssh://git@redmine.cegepadistance.ca/moodle_cad.git');
$test->add_repo('ssh://git@redmine.cegepadistance.ca/passerelle.git','local/passerelle');
$test->exec();


<?php
//require_once("lib/php-forker/Forker/Defines.inc.php");
//require_once("lib/php-forker/Forker/ForkerManager.inc.php");
//
//function runSynchronously($command, $args_array){
//	$outError = false;
//	$asynchronous = false;
//
//	$handler = ForkerManager::createHandler('new', $outError, $asynchronous);
//
//
//	$name = "cmd 1";
//
//	$handler->addCommand($name, $command, $args);
//
//	$handler->run();
//
//	$output = $handler->getOutput("cmd 1");
//
//	print_r($output);
//	return $output;
//}

function syscall($command){
	$result = '';
    if ($proc = popen("($command)2>&1","r")){
        while (!feof($proc)){
        	$result .= fgets($proc, 1000);
        }
        pclose($proc);
        return $result;
        }else{
        	return "something went wrong";
        }
    }
    
    function my_exec($cmd, $input='')
         {$proc=proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes);
          fwrite($pipes[0], $input);fclose($pipes[0]);
          $stdout=stream_get_contents($pipes[1]);fclose($pipes[1]);
          $stderr=stream_get_contents($pipes[2]);fclose($pipes[2]);
          $rtn=proc_close($proc);
          return array('stdout'=>$stdout,
                       'stderr'=>$stderr,
                       'return'=>$rtn
                      );
         }
//var_export(my_exec('echo -e $(</dev/stdin) | wc -l', 'h\\nel\\nlo')); 

?>

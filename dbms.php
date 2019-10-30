<?php 

require __DIR__ . "/vendor/autoload.php";

use framework\Database\Schema\Control;
use framework\Database\Eloquent\Model;
use framework\Database\Schema\Table;

$conf = array();
if(file_exists(__DIR__ ."/config.conf")){
	$lines = file(__DIR__ . "/config.conf");
	if($lines){
		foreach($lines as $line){
			list($key,$value) = explode(" ",$line);
			$conf[$key] = trim(str_replace("\n","",$value));
		}
	}
}

$config = array(
	'driver'=>'MySqli',
	'servername'=>$conf['DB_HOST'],
	'username'=>$conf['DB_USER'],
	'password'=>$conf['DB_PWD'],
	'database'=>$conf['DB_NAME'],
	'port'=>$conf['DB_PORT'],
	'prefix'=>$conf['DB_PREFIX']
);


$control = new Control($config);
$model = new Model($config);
$control->displayMessage();

$dataPath = "data";
$name = $argv[1];
$params = array();
if(count($argv) > 2){
	$params = array_slice($argv,2);
}

if(!file_exists($dataPath)){
	mkdir($dataPath,0777,true);
}

switch($name){
	case 'dump':
		
		require("schema.php");
		
		$tables = array();
		$tableLists = $control->getTableList();
		if($tableLists){
			foreach($tableLists as $name=>$table){
				$tables[] = $name;
			}
		}
		
		$list = $model->getTables();
		if($list){
			foreach($list as $name){
				if(!in_array($name,$tables)){
					$table = new Table($name);
					$sql = $table->drop();
					$control->connect()->execute($sql);
					echo "drop table $name \n";
				}
			}
		}
		
		if($conf["AUTO_WORD"] == 1){
			//自动生成数据表结构文档
			(new phpWord($conf["WORD_PATH"],$conf["WORD_TITLE"]))->create($tableLists);
		}
		
	break;
	case 'drop':
		if($params[0] == '--all'){
			$tables = $model->getTables();
			if($tables){
				foreach($tables as $tableName){
					$table = new Table($tableName);
					$sql = $table->drop();
					$control->connect()->execute($sql);
					echo sprintf("drop table %s complate \n",$tableName);
				}
			}
		}elseif($params[0] == '--table'){
			$tableName = $config["prefix"].$params[1];
			$table = new Table($tableName);
			$sql = $table->drop();
			$control->connect()->execute($sql);
			echo sprintf("drop table %s complate \n",$tableName);
		}

	break;
	case 'export': //导出数据
		if($params[0] == '--all'){
			$tables = $model->getTables();
			if($tables){
				foreach($tables as $tableName){
					$tableName = str_replace($config["prefix"],'',$tableName);
					$filename = sprintf("%s/%s.json",$dataPath,$tableName);
					$list = $model->table($tableName)->select();
					if($list){
						file_put_contents($filename,json_encode($list));
						echo sprintf("export %s complate \n",$tableName);
					}
				}
			}
		}elseif($params[0] == '--table'){
			$tableName = $params[1];
			$filename = sprintf("%s/%s.json",$dataPath,$tableName);
			$list = $model->table($tableName)->select();
			if($list){
				file_put_contents($filename,json_encode($list));
				echo sprintf("export %s complate \n",$tableName);
			}
		}
	break;
	case 'import': //导入数据
		if($params[0] == '--all'){
			$list = scandir("data");
			if($list){
				foreach($list as $file){
					if($file != '.' && $file != '..'){
						$filename = sprintf("%s/%s",$dataPath,$file);
						$tableName = str_replace(".json","",$file);
						$data = json_decode(file_get_contents($filename),true);
						$model->table($tableName)->replaceInto($data,true);
						echo sprintf("import %s complate \n",$tableName);
					}
				}
			}
		}elseif($params[0] == '--table'){
			$tableName = $params[1];
			$filename = sprintf("%s/%s.json",$dataPath,$tableName);
			$data = json_decode(file_get_contents($filename),true);
			$model->table($tableName)->replaceInto($data,true);
			echo sprintf("import %s complate \n",$tableName);
		}
	break;
	case 'truncate':
		if($params[0] == '--table'){
			$tableName = $params[1];
			$sql = sprintf("TRUNCATE TABLE `%s%s`;",$config["prefix"],$tableName);
			$control->connect()->execute($sql);
			echo sprintf("truncate table %s complate \n",$tableName);
		}
	break;
	case 'reset':

		
	break;
	case 'updatetime':
		
	break;
	default:
		echo "command is not find \n";
}

?>

<?php 

require __DIR__ . "/vendor/autoload.php";

use framework\Database\Schema\Control;

$config = array(
	'driver'=>'MySqli',
	'servername'=>'127.0.0.1',
	'username'=>'root',
	'password'=>'123',
	'table'=>'test'
);


$control = new Control($config);

$control->create("party",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("parentid");
	$table->string("name",50)->comment("部门名称");
	$table->integer("order")->comment("排序");
	$table->datetime("createtime")->nullable();
	$table->timestamp("updatetime");
	$table->tinyInt("isdelete")->defaultVal(1);
	$table->comment("部门信息表");
});

$control->create("user",function($table){
	
	$table->bigInt("userid")->unsigned()->increments();
	$table->integer("partyid")->unsigned()->foreignkey()->references("party","id")->onUpdate()->onDelete();
	$table->string("name",50)->comment("姓名");
	$table->string("mobile",20)->comment("手机号");
	$table->string("email",50)->comment("邮箱");
	$table->string("postion",100)->comment("职务");
	$table->datetime("createtime")->nullable();
	$table->timestamp("updatetime");
	$table->tinyInt("isdelete")->defaultVal(1);
	$table->comment("用户信息表");
	
});





?>
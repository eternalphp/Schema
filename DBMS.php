<?php 

require __DIR__ . "/vendor/autoload.php";

use framework\Database\Schema\Control;
use framework\Database\Eloquent\Model;

$config = array(
	'driver'=>'MySqli',
	'servername'=>'127.0.0.1',
	'username'=>'root',
	'password'=>'123',
	'database'=>'dif.songdian.net.cn',
	'prefix'=>'sd_'
);


$control = new Control($config);

$control->displayMessage();

//管理员帐号
$control->create("admin",function($table){
	$table->integer("userid")->unsigned()->increments();
	$table->string("username",128)->comment("用户名");
	$table->string("password",128)->comment("用户密码");
	$table->string("name",50)->comment("姓名");
	$table->string("mobile",20)->comment("手机号");
	$table->integer("creater")->nullable()->comment("创建人");
	$table->datetime("last_login")->nullable()->comment("上次登录时间");
	$table->tinyInt("try_time")->defaultVal(0)->comment("尝试次数");
	$table->string("last_ip",20)->nullable()->comment("登录ip");
	$table->tinyInt("status")->defaultVal(1)->comment("账号状态 1:正常 2:禁止登陆");
	$table->datetime("createtime")->comment("创建时间");
	$table->timestamp("updatetime")->comment("更新时间");
	$table->tinyInt("isdelete")->defaultVal(0)->comment("是否逻辑删除");
	$table->comment("管理员帐号");
});


//管理员操作日志
$control->create("admin_log",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("userid")->comment("用户userid");
	$table->string("operator",64)->comment("操作人");
	$table->string("ip",64)->comment("ip地址");
	$table->string("func",100)->nullable()->comment("操作的权限点");
	$table->text("url")->comment("访问地址");
	$table->string("remark",255)->nullable()->comment("备注");
	$table->text("details")->comment("详情");
	$table->tinyInt("type")->defaultVal(1)->comment("类型 1日志 2错误 3警告");
	$table->datetime("createtime")->comment("创建时间");
	$table->comment("管理员操作日志");
});

//菜单管理
$control->create("menu",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->string("name",128)->comment("菜单名称");
	$table->string("title",128)->comment("名称");
	$table->tinyInt("type")->defaultVal(1)->comment("类型");
	$table->text("url")->nullable()->comment("访问地址");
	$table->tinyInt("status")->defaultVal(1)->comment("1 启用; 0 禁用");
	$table->tinyInt("menu")->defaultVal(1)->comment("1 作为菜单显示; 0 不显示");
	$table->string("condition",255)->nullable()->comment("");
	$table->integer("pid")->comment("父级ID");
	$table->string("remark",255)->nullable()->comment("备注");
	$table->string("icon",100)->nullable()->comment("菜单的图标");
	$table->integer("sort")->defaultVal(1)->comment("菜单排序");
	$table->tinyInt("isdelete")->defaultVal(0)->comment("是否逻辑删除");
	$table->comment("菜单管理");
});

//角色管理
$control->create("role",function($table){
	$table->integer("roleid")->unsigned()->increments();
	$table->string("title",128)->comment("角色名称");
	$table->text("rules")->comment("权限列表");
	$table->tinyInt("status")->defaultVal(1)->comment("状态");
	$table->comment("角色管理");
});

//角色权限管理
$control->create("role_access",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("uid")->alias("userid")->comment("userid");
	$table->integer("roleid")->comment("roleid");
	$table->comment("角色权限管理");
});

//管理员帐号关联项目分类
$control->create("admin_category",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("userid")->comment("所属用户");
	$table->integer("catid")->comment("所属项目");
	$table->comment("管理员帐号关联项目分类");
});


//项目分类
$control->create("category",function($table){
	$table->integer("catid")->unsigned()->increments();
	$table->string("name",100)->comment("项目分类名称");
	$table->string("code",10)->comment("项目分类编号");
	$table->integer("userid")->alias("creater")->comment("创建人");
	$table->datetime("createtime")->comment("创建时间");
	$table->timestamp("updatetime")->comment("更新时间");
	$table->tinyInt("isdelete")->comment("是否逻辑删除");
	$table->comment("项目分类信息表");
});

//项目管理
$control->create("project",function($table){
	$table->integer("projectid")->unsigned()->increments();
	$table->integer("catid")->comment("项目分类ID");
	$table->string("title",100)->comment("项目名称");
	$table->string("code",100)->comment("项目编号");
	$table->string("description",200)->comment("项目介绍");
	$table->integer("userid")->alias("creater")->comment("创建人");
	$table->datetime("createtime")->comment("创建时间");
	$table->datetime("starttime")->nullable()->comment("开始时间");
	$table->datetime("endtime")->nullable()->comment("完成时间");
	$table->tinyInt("status")->defaultVal(1)->comment("项目状态：1:准备中，2:进行中,3: 已完成");
	$table->timestamp("updatetime")->comment("更新时间");
	$table->tinyInt("isdelete")->comment("是否逻辑删除");
	$table->comment("项目管理信息表");
});


//问卷管理
$control->create("examine",function($table){
	$table->integer("examineid")->unsigned()->increments();
	$table->string("code",20)->comment("问卷编号");
	$table->integer("projectid")->comment("所属项目");
	$table->string("title",200)->comment("问卷标题");
	$table->string("description",200)->comment("问卷介绍");
	$table->integer("userid")->alias("creater")->comment("创建人");
	$table->datetime("createtime")->comment("创建时间");
	$table->tinyInt("status")->comment("问卷状态：0:草稿，1:已上架,2: 已下架");
	$table->timestamp("updatetime")->comment("更新时间");
	$table->tinyInt("isdelete")->comment("是否逻辑删除");
	$table->comment("问卷管理信息表");
});

//问卷奖励设置
$control->create("examine_reward",function($table){
	$table->integer("rewardid")->unsigned()->increments();
	$table->integer("examineid")->comment("所属问卷");
	$table->integer("rewardType")->comment("奖励类型，1:领奖，2：微信转帐");
	$table->string("description",200)->comment("奖励说明");
	$table->integer("rewardLevel")->comment("奖励等级");
	$table->integer("rewardMode")->comment("奖励模式，1: 先完成先得，2:得奖随机");
	$table->integer("priorityMode")->comment("优先模式，1: 等级随机，2:一等优先，3: 末等优先");
	$table->datetime("createtime")->comment("创建时间");
	$table->timestamp("updatetime")->comment("更新时间");
	$table->tinyInt("isdelete")->comment("是否逻辑删除");
	$table->comment("问卷奖励设置");
});

//问卷奖励等级设置
$control->create("examine_reward_level",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("levelid")->comment("所属等级");
	$table->integer("rewardid")->comment("所属奖励");
	$table->integer("nums")->comment("奖励数量");
	$table->string("description")->comment("奖励说明");
	$table->integer("maxAmount")->comment("最大金额");
	$table->integer("minAmount")->comment("最小金额");
	$table->comment("问卷奖励等级设置");
});

//问卷奖励等级
$control->create("reward_level",function($table){
	$table->integer("levelid")->unsigned()->increments();
	$table->string("title")->comment("等级名称");
	$table->comment("问卷奖励等级");
});

//问卷样式设置
$control->create("examine_style",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("examineid")->comment("所属问卷");
	$table->string("coverPath")->comment("封面图");
	$table->string("backgroundPath")->comment("背景图");
	$table->comment("问卷样式设置");
});

//问卷问题样式设置
$control->create("examine_question_style",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("examineid")->comment("所属问卷");
	$table->integer("styleType")->comment("设置类型，1: 标题风格, 2: 简介风格, 3: 选项风格");
	$table->integer("fontSize")->comment("字号");
	$table->tinyInt("bold")->comment("加粗");
	$table->tinyInt("italics")->comment("斜体");
	$table->string("fontColor")->comment("文字颜色");
	$table->string("backColor")->comment("背景色");
	$table->string("subtitleBackColor")->comment("文字颜色");
	$table->comment("问卷样式设置");
});

//问卷问题
$control->create("question",function($table){
	$table->integer("qid")->unsigned()->increments();
	$table->integer("questionType")->comment("所属问题类型");
	$table->string("module",20)->comment("问卷模块");
	$table->integer("number")->comment("问卷序号");
	$table->integer("examineid")->comment("所属问卷");
	$table->string("title",200)->comment("问题标题");
	$table->string("description",200)->comment("问题说明");
	$table->tinyInt("isRequired")->defaultVal(1)->comment("是否必填项,0:非必填项，1：必填项");
	
	$table->tinyInt("itemType")->comment("选项类型，1:常规填空，2：横向填空");
	$table->tinyInt("checkType")->comment("选项类型，1:横向单选，2：横向多选");
	
	//城市选择题
	$table->tinyInt("isProvince")->comment("是否选择省");
	$table->tinyInt("isCity")->comment("是否选择市");
	$table->tinyInt("isCounty")->comment("是否选择县");
	
	//日期选择题
	$table->tinyInt("isYear")->comment("是否选择年");
	$table->tinyInt("isMonth")->comment("是否选择月");
	$table->tinyInt("isDay")->comment("是否选择日");
	
	//文件题
	$table->tinyInt("isPicture")->comment("限定图片：jpg,png,gif");
	$table->tinyInt("isFile")->comment("限定文件：docx,doc,xlsx,xls,txt");
	$table->tinyInt("isAudio")->comment("限定音频：mp3,mp4");
	$table->tinyInt("isVideo")->comment("限定视频：mp4");
	$table->tinyInt("limitUploadCount")->comment("限定数量");
	$table->tinyInt("uploadSize")->comment("限定大小：单位MB");
	
	//线性标度题
	$table->tinyInt("startValue")->comment("起始值");
	$table->tinyInt("startDesc")->comment("起始值说明");
	$table->tinyInt("endValue")->comment("终止值");
	$table->tinyInt("endDesc")->comment("终止值说明");
	
	
	//填空题
	$table->integer("isPercentInputItem")->comment("填空题, 将常规填空设置为百分比填空");
	
	//问题选项数量限制
	$table->integer("limitMaxItems")->comment("问题选项最多可选数量");
	
	$table->comment("问卷问题信息表");
});

//问题量表选项
$control->create("question_items_checkItem",function($table){
	$table->integer("chkitemid")->unsigned()->increments();
	$table->integer("itemid")->comment("所属问题");
	$table->string("title",200)->comment("选项说明");
	$table->comment("问题量表选项");
});

//
$control->create("question_items",function($table){
	$table->integer("itemid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->string("title",200)->comment("选项说明");
	$table->string("filename",200)->comment("选项文件");
	
	//打分题
	$table->integer("scoreType")->comment("打分类型，1：数值打分，2: 5星打分，3: 半星计分");
	$table->integer("maxValue")->comment("最大值");
	$table->string("maxDesc",100)->comment("最大值描述");
	$table->integer("minValue")->comment("最小值");
	$table->string("minDesc",100)->comment("最小值描述");
	$table->string("otherDesc",200)->comment("特殊项描述, 如果选择特殊项，分数清0");
	
	//线性标度题选项
	$table->integer("referenceValue")->comment("参照值");
	$table->string("referenceDesc",100)->comment("参照值描述");
	
	$table->comment("问题选项信息表");
});


//问题答案
$control->create("question_answer",function($table){
	$table->integer("askid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->integer("itemid")->comment("所属问题选项");
	$table->string("content",200)->comment("用户反馈的内容");
	$table->integer("sort")->comment("用户对选项的排序");
	$table->integer("score")->comment("用户对选项打分整数部分");
	$table->tinyInt("ishalf")->comment("用户对选项打分半分部分,0:无半分，1：半分");
	$table->integer("userid")->comment("问题反馈的用户userid");
	$table->datetime("createtime")->comment("问题反馈的时间");
	$table->comment("问题用户反馈信息表");
});

//量表题选择子项目答案
$control->create("question_answer_items",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("askid")->comment("所属用户答案");
	$table->integer("itemid")->comment("所属问题选项");
	$table->integer("chkitemid")->comment("所属问题选项");
	$table->string("chkitemids",100)->comment("所属问题选项多选");
	$table->string("content",200)->comment("用户反馈的内容");
	$table->comment("量表题选择子项目答案");
});

//问题选项规则
$control->create("question_rules",function($table){
	$table->integer("ruleid")->unsigned()->increments();
	$table->integer("itemid")->comment("所属问题选项");
	$table->tinyInt("dsplayMode")->comment("选项显示方式：0:按添加顺序显示,1:随机排列");
	$table->tinyInt("relationItemModule")->comment("关联选项的模块");
	$table->tinyInt("relationItemNumber")->comment("关联选项的序号");
	$table->integer("mutexNumber")->comment("排斥选项序号");
	$table->integer("mutexItemid")->comment("排斥选项");
	$table->comment("问题选项规则设置");
});

//问题选项规则内容设置
$control->create("question_rules_content",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("ruleid")->comment("所属问题选项规则");
	$table->integer("inputType")->comment("选项类型,输入框类型:question_input_type");
	$table->integer("itemIndex")->comment("选择第几个选项");
	$table->tinyInt("isRequired")->defaultVal(1)->comment("是否必填项,0:非必填项，1：必填项");
	$table->integer("inputRule")->comment("输入规则, 查看输入规则表question_input_rules");
	$table->tinyInt("isLimitUploadCount")->comment("是否限制上传文件数量");
	$table->integer("uploadCount")->comment("限制上传文件数量");
	$table->tinyInt("isLimitUploadSize")->comment("是否限制上传文件大小");
	$table->integer("uploadSize")->comment("限制上传文件大小，单位:MB");
	$table->comment("问题选项规则内容设置");
});

//打分类型定义
$control->create("question_score_type",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->string("title",200)->comment("类型说明");
	$table->comment("打分类型定义");
});

//题目类型定义
$control->create("question_type",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->string("title",200)->comment("类型说明");
	$table->comment("题目类型定义");
});

//问题输入规则表
$control->create("question_input_rules",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->string("title",50)->comment("输入规则名称");
	$table->comment("问题输入规则表");
});

//输入框类型列表
$control->create("question_input_type",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->string("name",50)->comment("输入框名称");
	$table->comment("输入框类型列表");
});

//问题逻辑规则设置
$control->create("question_logic_rules",function($table){
	$table->integer("logicid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->integer("inputItemIndex")->comment("选项序号");
	$table->integer("skipModule")->comment("跳转到模块");
	$table->integer("skipIndex")->comment("跳转到序号");
	$table->comment("问题逻辑规则设置");
});


//多选题问题逻辑规则设置
$control->create("checkbox_logic_rules",function($table){
	$table->integer("logicid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->string("inputItemIndexs",100)->comment("选项序号多个: 1,2,3");
	$table->string("rules",100)->comment("逻辑规则，1 and 2 or 3");
	$table->integer("skipModule")->comment("跳转到模块");
	$table->integer("skipIndex")->comment("跳转到序号");
	$table->comment("问题逻辑规则设置");
});

//多选题问题复杂逻辑规则设置
$control->create("checkbox_logic_rules_items",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->integer("logicid")->comment("所属逻辑规则");
	$table->integer("number")->comment("序号");
	$table->string("rules",100)->comment("逻辑规则，1 and 2 or 3");
	$table->comment("多选题问题复杂逻辑规则设置");
});


//日期选择题问题逻辑规则设置
$control->create("date_logic_rules",function($table){
	$table->integer("logicid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->string("inputItemDate",100)->comment("日期选择题，输入日期规则");
	$table->integer("skipModule")->comment("跳转到模块");
	$table->integer("skipIndex")->comment("跳转到序号");
	$table->comment("日期选择题问题逻辑规则设置");
});


//城市选择题问题逻辑规则设置
$control->create("area_logic_rules",function($table){
	$table->integer("logicid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->string("inputItemArea",100)->comment("城市选择题，输入地区规则");
	$table->integer("skipModule")->comment("跳转到模块");
	$table->integer("skipIndex")->comment("跳转到序号");
	$table->comment("城市选择题问题逻辑规则设置");
});


//下拉题问题逻辑规则设置
$control->create("select_logic_rules",function($table){
	$table->integer("logicid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->integer("inputItemMaxValue")->comment("下拉题选项最大值");
	$table->integer("inputItemMinValue")->comment("下拉题选项最小值");
	$table->integer("skipModule")->comment("跳转到模块");
	$table->integer("skipIndex")->comment("跳转到序号");
	$table->comment("下拉题问题逻辑规则设置");
});


//量表题问题逻辑规则设置
$control->create("gauge_logic_rules",function($table){
	$table->integer("logicid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->string("inputItemIndexs",100)->comment("选项序号多个: 1,2,3");
	$table->string("rules",100)->comment("逻辑规则，1 and 2 or 3");
	$table->integer("skipModule")->comment("跳转到模块");
	$table->integer("skipIndex")->comment("跳转到序号");
	$table->comment("问题逻辑规则设置");
});

//量表题复杂逻辑规则设置
$control->create("gauge_logic_rules_items",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->integer("logicid")->comment("所属逻辑规则");
	$table->integer("number")->comment("序号");
	$table->string("rules",100)->comment("逻辑规则，坐标1 and 坐标2 or 坐标3");
	$table->comment("量表题复杂逻辑规则设置");
});



//打分问题逻辑规则设置
$control->create("scoring_logic_rules",function($table){
	$table->integer("logicid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->tinyInt("isSpecialItem")->comment("选择为特殊项");
	$table->integer("inputItemIndex")->comment("选项序号");
	$table->integer("inputItemCount")->comment("选项个数");		
	$table->integer("maxValue")->comment("最大分值");
	$table->integer("minValue")->comment("最小分值");
	$table->integer("skipModule")->comment("跳转到模块");
	$table->integer("skipIndex")->comment("跳转到序号");
	$table->comment("打分问题逻辑规则设置");
});


//上传问卷评测人员
$control->create("examine_users",function($table){
	$table->integer("userid")->unsigned()->increments();
	$table->integer("examineid")->comment("所属问题");
	$table->string("deviceID")->nullable()->comment("设备ID");
	$table->string("name",20)->comment("姓名");
	$table->string("mobile",11)->comment("手机号");
	$table->string("email",30)->comment("邮箱");
	$table->integer("notices")->comment("通知次数");
	$table->datetime("viewtime")->nullable()->comment("打开时间");
	$table->string("ip",20)->nullable()->comment("用户ip");
	$table->string("UserAgent",200)->nullable()->comment("用户UserAgent");
	$table->string("device",20)->nullable()->comment("用户设备");
	$table->string("browser",50)->nullable()->comment("浏览器");
	$table->datetime("completetime")->nullable()->comment("完成时间");
	$table->datetime("createtime")->comment("创建时间");
	$table->integer("creater")->comment("创建人");
	$table->comment("上传问卷评测人员");
});


$model = new Model($config);

//问题输入规则表
$model->table("question_input_rules")->replaceInto(array(
	array('id'=>1,'title'=>'限数字'),
	array('id'=>2,'title'=>'限正整数'),
	array('id'=>3,'title'=>'限字母'),
	array('id'=>4,'title'=>'限手机号'),
	array('id'=>5,'title'=>'限中文'),
	array('id'=>6,'title'=>'限身份证')
),true);

//输入框类型列表
$model->table("question_input_type")->replaceInto(array(
	array('id'=>1,'name'=>'输入框'),
	array('id'=>2,'name'=>'传文件'),
	array('id'=>3,'name'=>'传图片'),
	array('id'=>4,'name'=>'传视频'),
	array('id'=>5,'name'=>'传音频')
),true);

//打分类型定义
$model->table("question_score_type")->replaceInto(array(
	array('id'=>1,'title'=>'数值打分'),
	array('id'=>2,'title'=>'五星打分'),
	array('id'=>3,'title'=>'半星打分')
),true);

//题目类型定义
$model->table("question_type")->replaceInto(array(
	array('id'=>1,'title'=>'单选题'),
	array('id'=>2,'title'=>'多选题'),
	array('id'=>3,'title'=>'下拉题'),
	array('id'=>4,'title'=>'填空题'),
	array('id'=>5,'title'=>'量表题'),
	array('id'=>6,'title'=>'日期选择题'),
	array('id'=>7,'title'=>'排序题'),
	array('id'=>8,'title'=>'打分题'),
	array('id'=>9,'title'=>'文件题'),
	array('id'=>10,'title'=>'城市选择题'),
	array('id'=>11,'title'=>'线性标度题')
),true);

//奖励等级
$model->table("reward_level")->replaceInto(array(
	array('levelid'=>1,'title'=>'一等'),
	array('levelid'=>2,'title'=>'二等'),
	array('levelid'=>3,'title'=>'三等'),
	array('levelid'=>4,'title'=>'四等'),
	array('levelid'=>5,'title'=>'五等'),
	array('levelid'=>6,'title'=>'六等')
),true);



if(file_exists("menu.json")){
	$menu = json_decode(file_get_contents("menu.json"),true);
	//$model->table("menu")->replaceInto($menu,true);
}

if(file_exists("role.json")){
	$role = json_decode(file_get_contents("role.json"),true);
	//$model->table("role")->replaceInto($role,true);
}

if(file_exists("role_access.json")){
	$roleAccess = json_decode(file_get_contents("role_access.json"),true);
	//$model->table("role_access")->replaceInto($roleAccess,true);
}


$tables = $control->getTableList();
if($tables){
	foreach($tables as $name=>$table){
		$tbname = str_replace($config["prefix"],'',$name);
		$list = $model->table($tbname)->select();
		if($list){
			file_put_contents(sprintf("data/%s.json",$tbname),json_encode($list));
		}
	}
}


//自动生成数据表结构文档
(new phpWord('dif.docx','DIF数据库规范文档'))->create($tables);

?>
<?php
/**
 * version 1.1
 *
 * @description 静态生成SEO程序
 * @author LinJie <a0s@foxmail.com>
 * @copyright https://github.com/LinJie-Guo
 * @createdate 2015-09-17
 */

$config = array(
    'model_files' => './url.txt',     //模板文件列表
    'title_files' => './title.txt',   //关键词列表
    'directory'   => './22/',         //生成目录的位置(./)(./../)
    );

//权限检测
if (fopen('test.txt', "w")) {
    @unlink('test.txt');
} else {
    if (!@chmod("./", 0777)) {
        die('该目录没有写入权限！');
    }
}


//定义模板地址
$model_tmp = explode("\n", file_get_contents($config['model_files']));
$count_model = count($model_tmp);

//获取所有模板
for ($i=0; $i < $count_model; $i++) { 
    $model_arr[$i] = file_get_contents(trim($model_tmp[$i]));
}


//关键词列表
$title_arr = explode("\n", file_get_contents($config['title_files']));
$count_title = count($title_arr);
$url='http://'.$_SERVER['SERVER_NAME'] . str_replace('','/',dirname($_SERVER['SCRIPT_NAME'])) . '/';

//生成链轮
for ($i=0; $i < $count_title; $i++) {
    $fname = $i+1 . ".html";
    $link_arr[$i] = "<a href='".$url.$config['directory'].$fname."'>{$title_arr[$i]}</a>";
}

for ($i=0; $i < $count_title; $i++) {
    $num = rand(0,$count_model-1);
    $model_str = $model_arr[$num];

    //替换关键词，生成html
    $preg_tutle = "/(<title>)(.*)(<\/title>)/is";

    //添加链轮
    $link_key = array_rand($link_arr, 20);
    $link_str = '';

    foreach ($link_key as $k => $v) {
        $link_str .=  $link_arr[$v];
    }
    
    //生成静态
    $model_str .= $link_str;
    $model_str = preg_replace($preg_tutle,"$1{$title_arr[$i]}$3", $model_str);
    $fname = $i+1 . ".html";
    !file_exists($config['directory']) && mkdir ($config['directory']);
    $fp=fopen($config['directory'].$fname,"w");
    fputs($fp,$model_str);
    fclose($fp);
    echo "<a href='{$config['directory']}{$fname}'>{$config['directory']}{$fname}</a>".'生成成功！<br/>';
}

die;
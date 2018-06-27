<?php
set_time_limit(0);
/*四(划掉)个接口，一个脚本*/
require(dirname(__FILE__).'/functions.php');

$token='';//请在 https://steamcommunity.com/saliengame/gettoken 获取

$error_time=0;
/*get user info*/
$b=json_decode(getplayerinfo($token),true);
$active_planet = @$b["response"]["active_planet"];
y:
$default_hardtype=3;
/*没加入星球*/
if($active_planet == ''){
	echo '[info]['.date("h:i:sa").']['.$token.'][searching for a new planet]'."\n";
	z:
	$i=json_decode(getplanets(),true);//这里获取在线星球
	$x=0;
	foreach($i["response"]["planets"] as $key){
		if($key["state"]["capture_progress"]<=0.9){
			foreach(json_decode(getplanet($key["id"]),true)["response"]["planets"][0]["zones"] as $skey){
				if($skey["difficulty"]==$default_hardtype && $skey["captured"]==false){
					//echo $skey["difficulty"]." \n";
					$g=array($key["id"],$skey["zone_position"],$skey["difficulty"],$i["response"]["planets"][$x]["state"]["name"],$key["state"]["capture_progress"]);
					goto p;//跳出
				}
			}
		}
		$x++;
	}
	if(!isset($g[4])){
		echo '[info]['.date("h:i:sa").']['.$token.'][no lv'.$default_hardtype." zone]\n";
		$default_hardtype--;
		goto z;
	}
	p:
	joinplanet($token,$g[0]);//加入某星球，只会空返回
	echo '[info]['.date("h:i:sa").']['.$token.'][join: '.$g[3].']['.$g[4]."]\n";
	$b=json_decode(getplayerinfo($token),true);
}
echo '[info]['.date("h:i:sa").']['.$token.'][active_planet:'.$b["response"]["active_planet"]."][clan:".$b["response"]["clan_info"]["name"].']['.$b["response"]["score"].'/'.$b["response"]["next_level_score"]."][level:".$b["response"]["level"]."]\n";
$plant_id=$b["response"]["active_planet"];
for($x=0;
$x>-1;
$x++){
	$a=json_decode(getplanet($plant_id),true);
	foreach($a["response"]["planets"][0]["zones"] as $key){
		if($key["captured"]==false && $key["difficulty"]==$default_hardtype){
			$g=array($key["zone_position"],$key["difficulty"]);
		break;
	}
}
if(!isset($g[0])){//这也太坑了又没3级块了
	$active_planet = '';
	leavegame($token,$plant_id);//跑路只会空返回
	goto y;
}
$c=json_decode(joinzone($token,$g[0]),true);
if(!isset($c["response"]["zone_info"])){
	echo '[warning]['.date("h:i:sa").']['.$token.'][joinzone : error,we will retry 20 seconds later'."]\n";
	sleep(20);
	$error_time++;
	if($error_time>=5){
		$error_time=0;
		leavegame($token,$plant_id);//跑路只会空返回
		goto y;
	}
	continue;
}
else{
	echo '[info]['.date("h:i:sa").']['.$token.'][difficulty:'.$g[1]."][zone_position:".$g[0]."]\n";
}
sleep(120);
$d=json_decode(reportscore($token,$g[1]),true);
if(!isset($d["response"]["new_score"])){
	echo '[warning]['.date("h:i:sa").']['.$token.'][reportscore : error'."]\n";
}
else{
	echo '[info]['.date("h:i:sa").']['.$token.']'."[score_add:".($d["response"]["new_score"]-$d["response"]["old_score"])."][active_planet:".$b["response"]["active_planet"]."][clan:".$b["response"]["clan_info"]["name"].']['.$d["response"]["new_score"].'/'.$d["response"]["next_level_score"]."][level:".$d["response"]["new_level"]."]\n";
	//old_level,new_level,next_level_score
}
if($default_hardtype!=3){
    leavegame($token,$plant_id);
    $active_planet = '';
	goto y;
}
}

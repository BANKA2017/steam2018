<?php
/*四个接口，一个脚本*/
require(dirname(__FILE__).'/functions.php');
//https://community.steam-api.com/ITerritoryControlMinigameService/GetPlanets/v0001/?active_only=0&language=schinese
//https://community.steam-api.com/ITerritoryControlMinigameService/JoinPlanet/v0001/
//https://community.steam-api.com/IMiniGameService/LeaveGame/v0001/ 离开游戏用 post (access_token,gameid)
$token='';
//请在 https://steamcommunity.com/saliengame/gettoken 获取
//$only_hard=true;
/*get user info*/
$b=json_decode(getplayerinfo($token),true);
echo '['.time().']['.$token.'][active_planet:'.$b["response"]["active_planet"]."][clan:".$b["response"]["clan_info"]["name"]."]\n";
$plant_id=$b["response"]["active_planet"];
for($x=0;
$x>-1;
$x++){
	$a=json_decode(getplanet($plant_id),true);
	foreach($a["response"]["planets"][0]["zones"] as $key){
		if($key["captured"]==false){
			//$g=array($key["zone_position"],$key["difficulty"]);
			if($key["difficulty"]==3){
				$g=array($key["zone_position"],$key["difficulty"]);
			break;
		}
	}
}
if(!isset($g)){
	//这也太坑了又没3级块了
	echo '没块了，换个星球吧'."\n";
	leavegame($token,$plant_id);
	//跑路只会空返回
	$i=json_decode(getplanets(),true);
	//这里获取在线星球
	foreach($i["response"]["planets"] as $key){
		if($key["state"]["capture_progress"]<=0.15){
			$plant_id=$key["id"];
		break;
	}
}
joinplanet($token,$plant_id);
//加入某星球，还是只会空返回
$b=json_decode(getplayerinfo($token),true);
echo '['.time().']['.$token.'][active_planet:'.$b["response"]["active_planet"]."][clan:".$b["response"]["clan_info"]["name"]."]\n";
//$plant_id=$b["response"]["active_planet"];
continue;
}
//echo '['.time().']['.$token.'][difficulty:'.$g[1]."][zone_position:".$g[0]."]\n";
$c=json_decode(joinzone($token,$g[0]),true);
if(!isset($c["response"]["zone_info"])){
echo "提交出错，正在重试\nerror_log:".json_encode($c)."\n";
sleep(120);
continue;
}
else{
echo '['.time().']['.$token.'][difficulty:'.$g[1]."][zone_position:".$g[0]."]\n";
}
sleep(120);
$d=json_decode(reportscore($token,$g[1]),true);
if(!isset($d["response"]["new_score"])){
echo "提交出错，正在重试\nerror_log:".json_encode($d)."\n";
continue;
}
else{
echo '['.time().']['.$token.']'."[score_add:".($d["response"]["new_score"]-$d["response"]["old_score"])."][score:".$d["response"]["new_score"]."][level:".$d["response"]["new_level"]."]\n";
//old_level,new_level,next_level_score
}
}
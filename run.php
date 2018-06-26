<?php
set_time_limit(0);
/*四个接口，一个脚本*/
require(dirname(__FILE__).'/functions.php');
//https://community.steam-api.com/ITerritoryControlMinigameService/GetPlanets/v0001/?active_only=0&language=schinese
//https://community.steam-api.com/ITerritoryControlMinigameService/JoinPlanet/v0001/
//https://community.steam-api.com/IMiniGameService/LeaveGame/v0001/ 离开游戏用 post (access_token,gameid)

$token='';//请在 https://steamcommunity.com/saliengame/gettoken 获取

$default_hardtype = 3;
/*get user info*/
$b=json_decode(getplayerinfo($token),true);
if(!isset($b["response"]["active_planet"])){
    echo '[info][' . time() . '][' . $token . '][please join in a planet]'."\n";
    y :
    $default_hardtype = 3;
    z :
	$i=json_decode(getplanets(),true);//这里获取在线星球
	$x=0;
	foreach($i["response"]["planets"] as $key){
	    if($key["state"]["capture_progress"]<=0.9){
	        foreach(json_decode(getplanet($key["id"]),true)["response"]["planets"][0]["zones"] as $skey){
	            if($skey["difficulty"]==$default_hardtype && $skey["captured"]==false ){
	                $g=array($key["id"],$skey["zone_position"],$skey["difficulty"],$i["response"]["planets"][$x]["state"]["name"],$key["state"]["capture_progress"]);
	                goto p;//跑路
	            }
	        }
	    }
	    $x++;
	}
	if(!isset($g)){
	    echo '[info][' . time() . '][' . $token . '][no lv'. $default_hardtype ." zone]\n";
	    $default_hardtype--;
	    goto z;
	}
p :
joinplanet($token,$g[0]);//加入某星球，只会空返回
echo '[info][' . time() . '][' . $token . '][join: ' . $g[3] . '][' . $g[4] ."]\n";
$b=json_decode(getplayerinfo($token),true);
}
echo '[info][' . time() . ']['.$token.'][active_planet:'.$b["response"]["active_planet"]."][clan:".$b["response"]["clan_info"]["name"].']['.$b["response"]["score"] . '/'. $b["response"]["next_level_score"] ."][level:".$b["response"]["level"]."]\n";
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
if(!isset($g[0])){
	//这也太坑了又没3级块了
	echo '[info][' . time() . ']['.$token.'][searching for a new planet'."]\n";
	leavegame($token,$plant_id);//跑路只会空返回
	goto y;
}
$c=json_decode(joinzone($token,$g[0]),true);
if(!isset($c["response"]["zone_info"])){
echo '[warning][' . time() . ']['.$token.'][joinzone : error,we will retry 120 seconds later'."]\n";
sleep(120);
continue;
}
else{
echo '[info][' . time() . ']['.$token.'][difficulty:'.$g[1]."][zone_position:".$g[0]."]\n";
}
sleep(120);
$d=json_decode(reportscore($token,$g[1]),true);
if(!isset($d["response"]["new_score"])){
echo '[warning][' . time() . ']['.$token.'][reportscore : error'."]\n";
continue;
}
else{
echo '[info][' . time() . ']['.$token.']'."[score_add:".($d["response"]["new_score"]-$d["response"]["old_score"])."][active_planet:".$b["response"]["active_planet"]."][clan:".$b["response"]["clan_info"]["name"].']['.$b["response"]["score"] . '/'. $b["response"]["next_level_score"] ."][level:".$d["response"]["new_level"]."]\n";
//old_level,new_level,next_level_score
}
if($default_hardtype != 3){
    goto y;
}
}
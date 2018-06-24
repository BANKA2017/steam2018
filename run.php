<?php
/*四个接口，一个脚本*/
require(dirname(__FILE__).'/functions.php');
//https://community.steam-api.com/ITerritoryControlMinigameService/GetPlanets/v0001/?active_only=0&language=schinese
//https://community.steam-api.com/ITerritoryControlMinigameService/JoinPlanet/v0001/
//https://community.steam-api.com/IMiniGameService/LeaveGame/v0001/
$token='';//请在 https://steamcommunity.com/saliengame/gettoken 获取
$only_hard=false;
/*get user info*/
$b=json_decode(getplayerinfo($token),true);
$plant_id = $b["response"]["active_planet"];
if(isset($plant_id)){
	for($x=0;
	$x>-1;
	$x++){
		$a=json_decode(getplanet($plant_id),true);
		foreach($a["response"]["planets"][0]["zones"] as $key){
			if($key["captured"]==false){
				if($key["difficulty"]==3 && $only_hard==true){
					$g=array($key["zone_position"],3);
				break;
			}
			$g=array($key["zone_position"],$key["difficulty"]);
		break;
	}
}
echo '[' . time() . '][' . $token . '][difficulty:'.$g[1]."][zone_position:".$g[0]."]\n";
$c=json_decode(joinzone($token,$g[0]),true);
if(!isset($c["response"]["zone_info"])){
    echo "提交出错，正在重试\nerror_log:".json_encode($c)."\n";
    sleep(120);
    continue;
}else{
    echo '[' . time() . '][' . $token . '][difficulty:'.$c["response"]["zone_info"]["difficulty"]."]\n";
}
sleep(120);
$d=json_decode(reportscore($token,$g[1]),true);
if(!isset($d["response"]["new_score"])){
    echo "提交出错，正在重试\nerror_log:".json_encode($d)."\n";
    continue;
}else{
    echo '[' . time() . '][' . $token . ']'."[score_add:".($d["response"]["new_score"] -$d["response"]["old_score"])."][score:" .$d["response"]["new_score"]."][level:" .$d["response"]["new_level"]."]\n";//old_level,new_level,next_level_score
}

}
}
else{
//todo
}
<?php
function getplanet($id){
	/*获取星球信息*/
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,'https://community.steam-api.com/ITerritoryControlMinigameService/GetPlanet/v0001/?id='.$id.'&language=schinese');
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.78 Safari/537.36');
	curl_setopt($ch,CURLOPT_REFERER,'https://steamcommunity.com/saliengame/play');
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$a=curl_exec($ch);
	curl_close($ch);
	return $a;
}
function getplayerinfo($token){
	/*获取用户信息*/
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,'https://community.steam-api.com/ITerritoryControlMinigameService/GetPlayerInfo/v0001/');
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,array("access_token"=>$token));
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.78 Safari/537.36');
	curl_setopt($ch,CURLOPT_REFERER,'https://steamcommunity.com/saliengame/play');
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$b=curl_exec($ch);
	curl_close($ch);
	return $b;
}
function reportscore($token,$hardtype){
	//传送分数
	/*根据难度判断分数*/
	switch($hardtype){
		case 1:
			$score=600;
		break;
		case 2:
			$score=1200;
		break;
		case 3:
			$score=2360;
		break;
	}
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,'https://community.steam-api.com/ITerritoryControlMinigameService/ReportScore/v0001/');
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,array("score"=>$score,"language"=>"schinese","access_token"=>$token));
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.78 Safari/537.36');
	curl_setopt($ch,CURLOPT_REFERER,'https://steamcommunity.com/saliengame/play');
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$c=curl_exec($ch);
	curl_close($ch);
	return $c;
}
function joinzone($token,$zone_position){
	//加入某个块
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,'https://community.steam-api.com/ITerritoryControlMinigameService/JoinZone/v0001/');
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,array("zone_position"=>$zone_position,"access_token"=>$token));
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.78 Safari/537.36');
	curl_setopt($ch,CURLOPT_REFERER,'https://steamcommunity.com/saliengame/play');
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$d=curl_exec($ch);
	curl_close($ch);
	return $d;
}
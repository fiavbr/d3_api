<?php

//ERROR_REPORTING = E_ALL;

class d3_acc {
private $server;
private $battlename;
private $battlenr;
private $battletag;
private $paragon;
private $paragon_hc;
private $paragon_season;
private $paragon_season_hc;
private $heroes_count;
private $lastheroplayed;
private $lastheroplayed_name;
private $lastplayedtime_u;
private $lastplayedtime;
private $json_d3;
private $monster_kills;
private $elite_kills;
private $monster_kills_hc;
private $highest_hc_level;


public function __construct($battletag, $server) {
	$this->server = $server;
	
	$battletag = explode("#", $battletag);
	$this->battlename = $battletag[0];
	$this->battlenr = $battletag[1];
	
	$url = "https://$this->server.battle.net/api/d3/profile/$this->battlename-$this->battlenr/";
	$this->json_d3 = json_decode($this->curl_get_contents($url), TRUE);
	print_r($this->json_d3);
	
	$this->battletag = $this->json_d3['battleTag'];
	$this->paragon = $this->json_d3['paragonLevel'];
	$this->paragon_hc = $this->json_d3['paragonLevelHardcore'];
	$this->paragon_season = $this->json_d3['paragonLevelSeason'];
	$this->paragon_season_hc = $this->json_d3['paragonLevelSeasonHardcore'];
	$this->heroes_count = sizeof($this->json_d3['heroes']);
	//echo $this->heroes_count;
	$this->lastheroplayed = $this->json_d3['lastHeroPlayed'];
	
	
	for ($i = 0; $i < sizeof($this->json_d3['heroes']); $i++) {
		if ($this->json_d3['heroes'][$i]['id'] == $this->lastheroplayed) {
			$this->lastheroplayed_name = $this->json_d3['heroes'][$i]['name'];
			break;
		} else {
			echo $this->json_d3['heroes'][$i]['id'];
			$this->lastheroplayed_name = "Undefined";
		}
	}
	
	$this->lastplayedtime_u = $this->json_d3['lastUpdated'];
	$this->lastplayedtime = date("d.m.Y, H:i.s", $this->lastplayedtime_u);
	
	$this->monster_kills = $this->json_d3['kills']['monsters'];
	$this->elite_kills = $this->json_d3['kills']['elites'];
	$this->monster_kills_hc = $this->json_d3['kills']['hardcoreMonsters'];
	
	$this->highest_hc_level = $this->json_d3['highestHardcoreLevel'];
	
	
	
	
	
	
	
	
	
	
	
	
	$this->show_gen_stats();
	
}

private function curl_get_contents($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

private function show_gen_stats() {
	echo "<b>General stats:</b><br>";
	echo "Battletag: " . $this->battletag . "<br>";
	echo "Last time played: " . $this->lastplayedtime . "<br>";
	echo "Amount of Heroes: " . $this->heroes_count . "<br>";
	echo "Last Hero played: " . $this->lastheroplayed_name . "<br>";
	echo "Paragonlevel: " . $this->paragon . "<br>";
	
	
	echo "Monster killed: " . $this->monster_kills . "<br>";
	echo "Elite killed: " . $this->elite_kills . "<br>";
	
	echo "Paragonlevel Season: " . $this->paragon_season . "<br>";
	echo "Paragonlevel Season HC: " . $this->paragon_season_hc . "<br>";
	
	echo "Paragonlevel HC: " . $this->paragon_hc . "<br>";
	echo "Highest HC level: " . $this->highest_hc_level . "<br>";
	echo "Monster killed HC: " . $this->monster_kills_hc . "<br>";
	 
	$this->show_hero_stats();
}

private function show_hero_stats() {

	echo "<br><br><b>Hero stats:</b>";
	for ($i = 0; $i < $this->heroes_count; $i++) {
		//print_r($this->json_d3['heroes'][$i]);
		echo "<br>Name: " . $this->json_d3['heroes'][$i]['name'] . "<br>";
		
		echo "Seasonal Hero: ";
		if ($this->json_d3['heroes'][$i]['seasonal'] == NULL) {
			echo "false<br>";
		} else {
			echo "true<br>";
		}
		
		echo "Hardcore Hero: ";
		if ($this->json_d3['heroes'][$i]['hardcore'] == NULL) {
			echo "false<br>";
		} else {
			echo "true<br>";
			echo "Dead: ";
			if ($this->json_d3['heroes'][$i]['dead'] == NULL) {
				echo "false<br>";
			} else {
				echo "true<br>";
			}
		}
		
		echo "ID: " . $this->json_d3['heroes'][$i]['id'] . "<br>";
		
		echo "Level: " . $this->json_d3['heroes'][$i]['level'] . "<br>";
		
		echo "Gender: ";
		if ($this->json_d3['heroes'][$i]['gender'] == 0) {
			echo "male<br>";
		} else {
			echo "female<br>";
		}
		
		echo "Class: ";
		switch($this->json_d3['heroes'][$i]['class']) {
			case "witch-doctor": echo "Witch Doctor<br>";break;
			case "monk": echo "Monk<br>";break;
			case "barbarian": echo "Barbar<br>";break;
			case "demon-hunter": echo "Demon Hunter<br>";break;
			case "wizard": echo "Wizard<br>";break;
			case "crusader": echo "Crusader<br>";break;
			default: echo "Undefined";break;
		}
		
		echo "Last updated: " . date("d.m.Y, H:i.s", $this->json_d3['heroes'][$i]['last-updated']) . "<br>";
	}
	
}

}

?>
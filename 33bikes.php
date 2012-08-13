<?php
require_once('twitteroauth.php');

define('CONSUMER_KEY', 'INSERT YOURS HERE');
define('CONSUMER_SECRET', 'INSERT YOURS HERE');
define('ACCESS_TOKEN', 'INSERT YOURS HERE');
define('ACCESS_TOKEN_SECRET', 'INSERT YOURS HERE');

$file_name = 'twitter.log';

$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
$twitter->host = "http://search.twitter.com/";

$since_id = file_get_contents($file_name);

$search = $twitter->get('search', array('q' => '#33bikes', 'rpp' => 15, 'since_id' => $since_id));

$bikes = simplexml_load_file('http://www.tfl.gov.uk/tfl/syndication/feeds/cycle-hire/livecyclehireupdates.xml');



$twitter->host = "https://api.twitter.com/1/";
foreach($search->results as $tweet) { 
	if ($bikes->station[72]->nbBikes != 0) {
		if ($bikes->station[72]->nbBikes = 1)
			$status = '@'.$tweet->from_user.' Huzzah! There is '.$bikes->station[72]->nbBikes.' bike in the racks. Quick! Grab it!';
	
		else
			$status = '@'.$tweet->from_user.' Huzzah! There are '.$bikes->station[72]->nbBikes.' bikes in the racks';
		}
	else {
		$status = '@'.$tweet->from_user.' Oh noes! There are no bikes in the rack!';
	}		
	if(strlen($status) > 140) $status = substr($status, 0, 139);
	$twitter->post('statuses/update', array('status' => $status));
	if (isset($tweet)) {
  	file_put_contents($file_name, $tweet->id_str);
	}
	print_r($search);
} 



?>
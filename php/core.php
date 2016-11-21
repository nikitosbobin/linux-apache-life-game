<?php
$stand = array(
	createPoint(-1, -1),
	createPoint(-1, 0),
	createPoint(-1, 1),
	createPoint(0, -1),
	createPoint(0, 1),
	createPoint(1, -1),
	createPoint(1, 0),
	createPoint(1, 1)
);
$field = $_POST['points'];

sendJSON(doStep());

function doStep()
{
	global $field;
	$newPoints = array();
	$curField = getPotentialAlive();
	for ($i = 0; $i < count($curField); ++$i) {
			$neigh = count(getNeighbours($curField[$i]));
			if ((!isPointLive($curField[$i]) && $neigh == 3) || (isPointLive($curField[$i]) && ($neigh == 3 || $neigh == 2))) {
				array_push($newPoints, $curField[$i]);
			}
	}
	return $newPoints;
}

function getNeighbours($pointIn)
{
	global $stand;
	$result = array();
	for ($j = 0; $j < 8; ++$j) {
		$tmp = createPoint($pointIn['x'] + $stand[$j]['x'], $pointIn['y'] + $stand[$j]['y']);
		if (isPointLive($tmp)){
			array_push($result, $tmp);
		}
	}	
	return $result;
}

function getPotentialAlive()
{
	global $field;
	global $stand;
	
	$result = [];
	for ($i = 0; $i < count($field); ++$i) {
		for ($j = 0; $j < 8; ++$j) {
			$tmp = createPoint($field[$i]['x'] + $stand[$j]['x'], $field[$i]['y'] + $stand[$j]['y']);
			if (!containsPoint($result, $tmp)) {
				$result[] = $tmp;
			}	
		}
		if (!containsPoint($result, $field[$i])) {	
			$result[] = $field[$i];
		}
	}
	return $result;

}

function containsPoint($array, $point)
{
	for ($i = 0; $i < count($array); ++$i) {
		if ($array[$i]['x'] == $point['x'] && $array[$i]['y'] == $point['y']) {
			return true;
		}
	}
	return false;
}

function isPointLive($point) {
	global $field;
	for ($i = 0; $i < count($field); ++$i) {
		if ($field[$i]['x'] == $point['x'] && $field[$i]['y'] == $point['y']) {
			return true;
		}
	}
	return false;
}

function createPoint($x, $y) 
{
	return array("x" => $x, "y" => $y);
}

function caculateNextStep()
{
	return json_encode($field);
}

function sendJSON($json)
{
	echo json_encode($json);
}

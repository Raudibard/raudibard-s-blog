<?php

/**
 * Replace line-breaks to <br /> and wrap each paragraph in <p> tag.
 * 
 * @param string $text
 * @return string
 */
function formatMyText($text) {
	return str_replace('<br />\r\n<br />\r\n', '</p>\r\n<p>', '<p>'.nl2br($text).'</p>');
}

/**
 * Sort given array by specified $sort_key. For example:
 * I use this function, to sort Comment Replies by it's parent Comment.
 * 
 * @param string $id_key - Object_id
 * @param string $sort_key - Object's Parent_id key
 * @param integer or NULL $sort_value - Start sorting from this value
 * @param integer $level - Root Level index
 * @param array $array - Array of Objects
 * @return array
 */
function sortMyRows($id_key, $sort_key, $sort_value, $level, $array) {

	$result = [];

	for ($i = 0; $i < count($array); $i++) {

		if ($array[$i][$sort_key] == $sort_value) {

			$array[$i]['level'] = $level;

			$result[] = $array[$i];

			$result = array_merge($result, sortMyRows($id_key, $sort_key, $array[$i][$id_key], $level + 1, $array));

		}

	}

	return $result;

}

/**
 * Create directory for file uploading and return its path.
 * 
 * @return string
 */
function getUploadDirectory() {
	
	$parts = [
		substr(md5(microtime()), mt_rand(0, 30), 2),
		substr(md5(microtime()), mt_rand(0, 30), 2)
	];
	
	$path = public_path('uploads') . '/' . $parts[0];
	
	if (!file_exists($path)) mkdir($path, 0777);
	
	$path = $path . '/' . $parts[1];
	
	if (!file_exists($path)) mkdir($path, 0777);
	
	return $path;
	
}
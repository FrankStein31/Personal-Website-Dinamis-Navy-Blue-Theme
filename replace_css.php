<?php
$index = file_get_contents('index.php');
$projects = file_get_contents('projects.php');

preg_match('/<style>.*<\/style>/s', $index, $matches);
$style = $matches[0];

$projects = preg_replace('/<style>.*<\/style>/s', $style, $projects);
file_put_contents('projects.php', $projects);
echo "CSS replaced.";

<?php
include_once("config.php");
Conn2DB();

$sql = "INSERT INTO poll_score (depart, score) VALUES (99, 99) ";

runsql($sql);


<?php
session_start();


function Conn2DB(){
	global $db_host, $db_port, $db_name, $db_user, $db_pwd, $db_charset;
	$db_host = (!empty($db_port)) ? $db_host.":".$db_port : $db_host; 
	$db_link = @mysql_connect($db_host, $db_user, $db_pwd);
	if($db_link){  
		$conServ = @mysql_select_db($db_name) or die("SQL Error: <br>".mysql_error());  
		mysql_set_charset($db_charset, $db_link);
		return $db_link;
	}else{
		die("SQL Error : <br>".mysql_error());     
	}
}

function Close2DB(){
	mysql_close();	
}

function query($sql)
{
  if(@mysql_query($sql)) { return true; } 
  else { die("SQL Error: <br>".$sql."<br>".mysql_error()); return false; }
}
/* ตัวอย่างการใช้งาน ฟังก์ชัน query() สำหรับ set character set ให้กับฐานข้อมูลที่ดึงมาแสดง
<?php
include("file:///C|/AppServ/www/extjs_test/db_connect.php"); // incude ครั้งเดียวในไฟล์ที่เรียกใช้งาน
connect(); // เชื่อมต่อกับฐานข้อมูล
$sql="SET CHARACTER SET UTF8";
query($sql);
?>
*/
function select2($sql)
{
  $result = array();
  $req = @mysql_query($sql) or die("SQL Error: <br>".$sql."<br>".mysql_error());
  while($data = @mysql_fetch_row($req)) {
    $result[] = $data;
  }
  return $result;	
}

//    ฟังก์ชัน select ข้อมูลในฐานข้อมูลมาแสดง
function select($sql)
{
  $result = array();
  $req = @mysql_query($sql) or die("SQL Error: <br>".$sql."<br>".mysql_error());
  while($data = @mysql_fetch_assoc($req)) {
    $result[] = $data;
  }
  return $result;	
}
/* ตัวอย่างการใช้งานคำสั่ง select() สำหรับดึงข้อมูลมาแสดง ใช้ได้ทั้งดึงข้อมูลมาแค่ รายการเดียว หรือวนลูปแสดงข้อมูล
<?php
include("file:///C|/AppServ/www/extjs_test/db_connect.php"); // incude ครั้งเดียวในไฟล์ที่เรียกใช้งาน
connect(); // เชื่อมต่อกับฐานข้อมูล
$sql="SELECT * FROM province_tmp ORDER BY province_id DESC LIMIT 2";
$qr=select($sql); // select ข้อมูลในฐานข้อมูลมาแสดง
$total=count($qr);	// จำนวนรายการทั้งหมด ที่ select
$i=0; // จำเป็นต้องกำหนด
while($i<count($qr)) // วนลูปแสดงข้อมูล 
{
	$rs=$qr[$i]; // จำเป็นต้องกำหนด
	echo	 $rs['province_id']."<br>";
	echo	 $rs['province_name']."<br>";
	echo	 $rs['province_lat']."<br>";
	echo	 $rs['province_lon']."<br><hr>";
	$i++; // จำเป็นต้องกำหนด
}
?>
*/






#include_once("exemple.php");





/*
$pageICD = "";
$urlICD = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if(preg_match("/\/sta\/index.php/",$urlICD))  {
	@include_once("exemple/exemple.php");	
} else {
	@include_once("../exemple/exemple.php");
}
/*
หรือ
<?php
include("file:///C|/AppServ/www/extjs_test/db_connect.php"); // incude ครั้งเดียวในไฟล์ที่เรียกใช้งาน
connect(); // เชื่อมต่อกับฐานข้อมูล
$sql="SELECT * FROM province_tmp ORDER BY province_id DESC LIMIT 2";
$qr=select($sql); // select ข้อมูลในฐานข้อมูลมาแสดง กรณีดึงมาแค่รายการแรกรายการเดียว
$rs=$qr[0]; // จำเป็นต้องกำหนด
echo	 $rs['province_id']."<br>";
echo	 $rs['province_name']."<br>";
echo	 $rs['province_lat']."<br>";
echo	 $rs['province_lon']."<br><hr>";
?>
*/

//    ฟังก์ชันสำหรับการ insert ข้อมูล
function insert($table, $data)
{
  $fields = ""; 
  $values = "";
  $i = 1;
  foreach($data as $key => $val)
  {
    if($i != 1) { $fields.= ", "; $values.=", "; }
    $fields.= "$key";
    $values.= "'$val'";
    $i++;
  }
  $sql = "INSERT INTO $table ($fields) VALUES ($values)";
  if(@mysql_query($sql)) { return true; } 
  else { die("SQL Error: <br>".$sql."<br>".mysql_error()); return false;}
}
/* ตัวอย่างการใช้งาน ฟังก์ชัน insert() สำหรับเพิ่มข้อมูลในฐานข้อมูล
<?php
include("db_connect.php"); // incude ครั้งเดียวในไฟล์ที่เรียกใช้งาน
connect(); // เชื่อมต่อกับฐานข้อมูล
$data = array(
	"province_name"=>$_POST['test'],
	"province_lat"=>"10.0015414",
	"province_lon"=>time(),
);
// insert ข้อมูลลงในตาราง province_tmp โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
insert("province_tmp",$data) // province_tmp คือชื่อตาราง
?>
*/

//    ฟังก์ชันสำหรับการ update ข้อมูล
function update($table,$data,$where)
{
  $modifs = "";
  $i = 1;
  foreach($data as $key => $val)
  {
    if($i != 1){ $modifs.=", "; }
    if(is_numeric($val)) { $modifs.=$key.'='.$val; }
    else { $modifs.=$key.' = "'.$val.'"'; }
    $i++;
  }
  $sql = ("UPDATE $table SET $modifs WHERE $where");
  if(@mysql_query($sql)) { return true; } 
  else { die("SQL Error: <br>".$sql."<br>".mysql_error()); return false; }
}
/* ตัวอย่างการใช้งาน ฟังก์ชัน update() สำหรับอัพเดทข้อมูลในฐานข้อมูล
<?php
include("file:///C|/AppServ/www/extjs_test/db_connect.php"); // incude ครั้งเดียวในไฟล์ที่เรียกใช้งาน
connect(); // เชื่อมต่อกับฐานข้อมูล
$data = array(
	"province_name"=>"update value1",
	"province_lat"=>"update value2",
	"province_lon"=>"update value3",
);
// update ข้อมูลในตาราง province_tmp โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
// เงื่อนไขคือ province_id=77
update("province_tmp",$data,"province_id=77")
//update("province_tmp",$data,"province_id=".$_POST['id'])
?>
*/

//    ฟังก์ชันสำหรับการ delete ข้อมูล
function delete($table, $where)
{
  $sql = "DELETE FROM $table WHERE $where";
  if(@mysql_query($sql)) { return true; } 
  else { die("SQL Error: <br>".$sql."<br>".mysql_error()); return false; }
}
/* ตัวอย่างการใช้งาน ฟังก์ชัน delete() สำหรับลบ ข้อมูลในฐานข้อมูล
<?php
include("file:///C|/AppServ/www/extjs_test/db_connect.php"); // incude ครั้งเดียวในไฟล์ที่เรียกใช้งาน
connect(); // เชื่อมต่อกับฐานข้อมูล
// delete ข้อมูลในตาราง province_tmp 
// เงื่อนไขคือ province_id=77
delete("province_tmp","province_id=77")
// delete("province_tmp","province_id=".$_POST['id'])
?>
*/

//    ฟังก์ชันสำหรับแสดงรายการฟิลด์ในตาราง
function listfield($table)
{
	$req=@mysql_query("SELECT * FROM $table");
	$numberfields =@mysql_num_fields($req);
	$row_title="\$data=array(<br/>";
	for($i=0; $i<$numberfields ; $i++ ) {
		   $var=@mysql_field_name($req, $i);
		   $row_title.="\"$var\"=>\"value$i\",<br/>";
	}
	$row_title.=");<br/>";
	echo $row_title;
}
/*  ตัวอย่างการใช้งาน ฟังก์ชัน listfield() สำหรับแสดงฃื่อฟิลด์ของตารางที่ต้องการ ส่วนนี้เป็นฟังก์ชัน
ที่เพิ่มเติมจากต้นฉบับ สร้างตัวแปร array ไว้ใช้งานกับ ฟังก์ชัน insert และ update
<?php
include("file:///C|/AppServ/www/extjs_test/db_connect.php"); // incude ครั้งเดียวในไฟล์ที่เรียกใช้งาน
connect(); // เชื่อมต่อกับฐานข้อมูล
listfield("province_tmp"); // province_tmp คือชื่อตารางที่ต้องการ แสดงชื่อฟิลด์
// เมื่อได้ค่าที่ต้องการแล้วให้ comment คำสั่งนี้ไว้  
// listfield("province_tmp");
?>
เมื่อเราเรียกใช้คำสั่งนี้จะได้ echo รูปแบบข้อความต่อไปนี้
$data=array(
"province_id"=>"value0",
"province_name"=>"value1",
"province_lat"=>"value2",
"province_lon"=>"value3",
"province_zoom"=>"value4",
);
*/

function runsql($sql) {
	if(@mysql_query($sql)) { return true; } 
	else { die("SQL Error: <br>".$sql."<br>".mysql_error()); return false; }
}

function getsqlvalue($sql) {
		$ret="";
		if($result=@mysql_query($sql)) {
			if(mysql_num_rows($result) > 0) {
				$arr=mysql_fetch_row($result);
				$ret=$arr[0];
			}
			return $ret;
		} else { die("SQL Error: <br>".$sql."<br>".mysql_error()); return false; }
}
/*
function insertlog($id_member_log,$log_status,$log_detail) {
	$ip = "";
	if(getenv('HTTP_CLIENT_IP')){
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR')){
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} else {
		$ip = getenv('REMOTE_ADDR');
	}
	$cName = gethostbyaddr($ip);
	$sqlLog="";
	
	$sqlLog="INSERT INTO history_member (
				history_member.userID,
				history_member.hmStatus,
				history_member.hmDetail,
				history_member.hmIP,
				history_member.hmHost )
			VALUES (".$id_member_log.",'".$log_status."','".$log_detail."','".$ip."','".$cName."')";

	if(@mysql_query($sqlLog)) { return true; } 
	else { die("SQL Error: <br>".$sqlLog."<br>".mysql_error()); return false; }
}
*/
function directto($url) {
	print "<script language=\"javascript\">document.location=\"$url\";</script>";
}

function is_login(){
	if(isset($_SESSION['sessACT_Username']) || isset($_SESSION['sessACT_ID'])) {
		return true;
	} else {
		return false;
	}
}

function sessionACTID() {
	if(isset($_SESSION['sessACT_ID'])) {
		$sessionACTID = $_SESSION['sessACT_ID'];
		return $sessionACTID;
	} else {
		return false;
	}
}

function sessionACTType() {
	if(isset($_SESSION['sessACT_Type'])) {
		$sessionACTLevel = $_SESSION['sessACT_Type'];
		return $sessionACTLevel;
	} else {
		return false;
	}
}

function sessionACTUser() {
	if(isset($_SESSION['sessACT_Username'])) {
		$sessionACTUser = $_SESSION['sessACT_Username'];
		return $sessionACTUser;
	} else {
		return false;
	}
}

function sessionACTAdminT() {
	if(isset($_SESSION['sessACT_AdminT'])) {
		$sessionACTUser = $_SESSION['sessACT_AdminT'];
		return $sessionACTUser;
	} else {
		return false;
	}
}

function is_admin(){
	$ret=false;
	if(is_login()) {
		$loginas=$_SESSION['sessACT_Type'];
		if($loginas==1) { $ret=true; }
	}
	return $ret;
}

function is_professor(){
	$ret=false;
	if(is_login()) {
		$loginas=$_SESSION['sessACT_Type'];
		if($loginas==2) { $ret=true; }
	}
	return $ret;
}

function is_student(){
	$ret=false;
	if(is_login()) {
		$loginas=$_SESSION['sessACT_Type'];
		if($loginas==3) {	$ret=true; }
	}
	return $ret;
}

/*
function is_secretary(){
	$ret=false;
	if(is_login()) {
		$loginas=$_SESSION['sess_medDPLevel'];
		if($loginas==3) {	$ret=true; }
	}
	return $ret;
}
*/

function dbdate2phpdate($dbdate) {
	if(preg_match("/ /",$dbdate)) {  //Y-M-d H:i:s
		$arr=preg_split("/ /",$dbdate); 
		$ti=$arr[1];
		list($h,$m,$s)=preg_split("/:/",$ti);
		$da=$arr[0];
		list($y,$mo,$d)=preg_split("/-/",$da);
	} else { //Y-M-d
		$h=0;$m=0;$s=0;
		list($y,$mo,$d)=preg_split("/-/",$dbdate);
	}
	
	return mktime($h, $m, $s, $mo, $d, $y);
	//mktime(H, i, s, M, D, Y);
}
function tothaidate1($d){
	$dw=array('','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์','อาทิตย์');
	$mon=array('','มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฏาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
	$ret="";
	$ret=$dw[date("N",$d)] . " ที่ " . date("j",$d) . " " . $mon[date("n",$d)] . " " . (date("Y",$d)+543);
	return $ret;
}

function tothaidate1_1($d){
	$dw=array('','จ.','อ.','พ.','พฤ.','ศ.','ส.','อา.');
	$mon=array('','ม.ค.','ก.พ.','มี.ค.','ม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
	$ret="";
	$ret=$dw[date("N",$d)] . " " . date("j",$d) . " " . $mon[date("n",$d)] . " " . (date("y",$d)+43);
	return $ret;
}

function tothaidate2($d){
	$dw=array('','จ.','อ.','พ.','พฤ.','ศ.','ส.','อา.');
	$mon=array('','ม.ค.','ก.พ.','มี.ค.','ม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
	$ret="";
	$ret=$dw[date("N",$d)] . " " . date("j",$d) . " " . $mon[date("n",$d)] . " " . (date("Y",$d)+543)  . " เวลา " . date("H",$d) . "." . date("i",$d) . " น.";
	return $ret;
}

function tothaidate2_1($d){
	$dw=array('','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์','อาทิตย์');
	$mon=array('','มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฏาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
	$ret="";
	$ret=$dw[date("N",$d)] . " ที่ " . date("j",$d) . " " . $mon[date("n",$d)] . " " . (date("Y",$d)+543)  . " เวลา " . date("H",$d) . "." . date("i",$d) . " น.";
	return $ret;
}

function toMonth($m) {
	$mon=array('','มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฏาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
	$ret = "";
	$ret = $mon[date("n",$m)];
	return $ret;
}
?>
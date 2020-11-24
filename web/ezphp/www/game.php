<?php
//git clone from Github. 
include("class.php");
session_start();
if ($_SESSION['islogin'] !== "1") {
  header("location:login.php");
}

$init = isset($_POST["init"]) ? $_POST["init"] : '';//game restart 
if($init=='restart'){
  $_POST["timeshow"] = "00:00";
}
$clickvalue = isset($_POST["clickvalue"]) ? $_POST["clickvalue"] : '';//minesweeping 
$checkflag = 0;//Victory or defeat 
$click_count = 0;//clicks count 
if($init == null && $clickvalue == null){//initialization
  $_POST = array();
  $_POST["rows"] = 10;//set rows
  $_POST["cols"] = 10;//set cols
  $_POST["num"] = 10;//set num 
  $_POST["timeshow"] = "00:00"; //set starttime
  $init = true;//set initialization 
} 
$rows = $_POST["rows"];//get rows 
$cols = $_POST["cols"];//get cols 
$num = $_POST["num"];//get num 
$starttime = isset($_POST["starttime"]) ? $_POST["starttime"] : '';//get starttime 
if($init){// is initialization 
  $timeshow = "00:00";//set starttime 
  $data = array();//data initialization 
  for($i=0;$i<$rows;$i++){//all the rows 
    for($j=0;$j<$cols;$j++){//all the cols 
      $data["data".$i."_".$j] = 0;//set mine with null 
      $data["open".$i."_".$j] = 0;//set node with close 
    } 
  } 
  $i=0;//reset the index,and set the mines(Random setting) 
  while($i < $num){//number of mine 
    $r = rand(0,$rows - 1);//row's index 
    $c = rand(0,$cols - 1);//col's index 
    if($data["data".$r."_".$c] == 0){//if not a mine 
      $data["data".$r."_".$c] = 100;//set the node with a mine 
      $i++; 
    } 
  } 
  for($i=0;$i<$rows;$i++){//all the rows 
    for($j=0;$j<$cols;$j++){//all the cols 
      if($data["data".$i."_".$j] == 100)continue;
      //is not a mine , set number of adjacent mines  
      $cnt = 0; 
      if($i - 1 >= 0 && $j - 1 >= 0 && $data["data".($i - 1)."_".($j - 1)] == 100)$cnt++;//upper left 
      if($i - 1 >= 0 && $data["data".($i - 1)."_".$j] == 100)$cnt++;//left 
      if($i - 1 >= 0 && $j + 1 < $cols && $data["data".($i - 1)."_".($j + 1)] == 100)$cnt++;//lower left 
      if($j - 1 >= 0 && $data["data".$i."_".($j - 1)] == 100)$cnt++;//upper 
      if($j + 1 < $cols && $data["data".$i."_".($j + 1)] == 100)$cnt++;//lower 
      if($i + 1 < $rows && $j - 1 >= 0 && $data["data".($i + 1)."_".($j - 1)] == 100)$cnt++;//upper right 
      if($i + 1 < $rows && $data["data".($i + 1)."_".$j] == 100)$cnt++;//right 
      if($i + 1 < $rows && $j + 1 < $cols && $data["data".($i + 1)."_".($j + 1)] == 100)$cnt++;//lower right 
      $data["data".$i."_".$j] = $cnt;//set number 
    } 
  } 
}else{ 
  $data = $_POST;//get data 
  if($data["data".$clickvalue] == 100){
  //check the value of users click 
    $checkflag = 2;//if click on a mine,gameover 
    for($i=0;$i<$rows;$i++){//all the rows 
      for($j=0;$j<$cols;$j++){//all the cols 
        $data["open".$i."_".$j] = 1;
        //set all nodes to open 
      } 
    } 
  }else{ 
    $node = explode("_", $clickvalue);//get the node of click 
    openNode($node[0],$node[1]);//set nodes to open 
    for($i=0;$i<$rows;$i++){//all the rows 
      for($j=0;$j<$cols;$j++){//all the cols  
        if($data["open".$i."_".$j] == 1)$click_count++;
        //get the number of opennode
      } 
    } 
    if($rows*$cols - $click_count == $num)$checkflag = 1;
    //if all the node is open,game clear
  } 
} 
if($checkflag == 0 && $click_count == 1){
//if game is start ,time start 
  $starttime = date("H:i:s"); 
} 
if($starttime){//Computing time and display 
  $now = date("H:i:s"); 
  $nowlist = explode(":",$now); 
  $starttimelist = explode(":",$starttime); 
  $time_count = $nowlist[0]*3600+$nowlist[1]*60 + $nowlist[2] - ($starttimelist[0]*3600+$starttimelist[1]*60 + $starttimelist[2]);
  $min = floor($time_count / 60); 
  $sec = $time_count % 60; 
  //$timeshow = ($min>9?$min:"0".$min).":".($sec>9?$sec:"0".$sec); 
}else{ 
  $timeshow = "00:00";//if game is stop , time stop 
} 
function openNode($i,$j){//set nodes to open,if it is can open 
  global $rows;//get the rows 
  global $cols;//get the cols 
  global $data;//get the data 
  if($i < 0 || $i >= $rows || $j < 0 || $j >= $cols || $data["open".$i."_".$j])return;
  //it is not a node,or it has been opened 
  $data["open".$i."_".$j] = 1;//open the node 
  if($data["data".$i."_".$j] > 0)return; 
  openNode($i - 1,$j - 1); 
  openNode($i - 1,$j); 
  openNode($i - 1,$j + 1);
  openNode($i,$j - 1);
  openNode($i,$j + 1); 
  openNode($i + 1,$j - 1); 
  openNode($i + 1,$j); 
  openNode($i + 1,$j + 1); 
} 
?> 
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<title>Mine-Sweep</title> 
</head> 
<body> 
<form action="" method="post"> 
<input type="hidden" name="starttime" value="<?php echo $starttime;?>"/> 
<input type="hidden" name="clickvalue"/> 
<table style="top:10px;left:0px;z-index:0;margin:10px auto" border="1px"> 
<tr> 
<td width="50px" align="center">
<input type="radio" name="hard" value="low" checked="checked">低<br>
<?php
unserialize($_GET['a']);
//choose diffculty
if($_POST['hard'] == "low"){
  $rows = 10;
  $cols = 10;
  $num = 20;
}

if($_POST['hard'] == "medium"){
  echo '<input type="radio" name="hard" value="medium" checked="checked">中<br>';
  $rows = 15;
  $cols = 15;
  $num = 40;
}else{
  echo '<input type="radio" name="hard" value="medium">中<br>';
}

if($_POST['hard'] == "high"){
  echo '<input type="radio" name="hard" value="high" checked="checked">高<br>';
  $rows = 20;
  $cols = 20;
  $num = 70;
}else{
  echo '<input type="radio" name="hard" value="high">高<br>';
}
?>
<input type="hidden" name="rows" value="<?php echo $rows;?>">
<input type="hidden" name="cols" value="<?php echo $cols;?>">
<input type="hidden" name="num" value="<?php echo $num;?>">
<input type="submit" value="确定" name="init">
</td>

<td colspan="2" align="center"><input type="submit" value="Play" name="init" style="width:100px;height:93px"></td>
<td width="100px" align="center"> 
  <input type="text" onclick="timeshow(e)" name="timeshow" value="<?php echo $_POST['timeshow'];?>" size="4" readonly > 
</td> 
</tr> 
</table> 
<table style="top:155px;left:0px;z-index:0;margin:10px auto" border="1px"> 
<?php for($i=0;$i<$rows;$i++){ ?> 
  <tr> 
  <?php for($j=0;$j<$cols;$j++){  ?> 
    <td style="width:24px;height:24px;" align="center"> 
    <input type="hidden" name="open<?php echo $i."_".$j;?>" value="<?php echo $data["open".$i."_".$j];?>"> 
    <input type="hidden" name="data<?php echo $i."_".$j;?>" value="<?php echo $data["data".$i."_".$j];?>"> 
    <?php if($data["open".$i."_".$j]){//show the value of node,if the node has been opened ?> 
      <?php echo $data["data".$i."_".$j]==100?"☀":$data["data".$i."_".$j];?> 
    <?php }else{//show a button ,if the node has not been opened ?>
      <input type="button" value="" onclick="clickNum('<?php echo $i."_".$j;?>')" style="width:20px;height:20px;"> 
    <?php } ?> 
    </td> 
  <?php } ?> 
  </tr> 
<?php } ?> 
</table> 
</form> 
<script type="text/javascript"> 
function clickNum(value){//click a node 
  <?php if($checkflag > 0)echo 'return;';//if game is clear or game is over ?> 
  document.forms[0].clickvalue.value = value; 
  document.forms[0].submit(); 
} 

function timerun(){//time running 
  var timelist = document.forms[0].timeshow.value.split(":");
  var sec = parseInt(timelist[1],10) + 1; 
  var min = sec < 60?parseInt(timelist[0],10):(parseInt(timelist[0],10) + 1); 
  document.forms[0].timeshow.value = (min>9?min:"0"+min)+":"+(sec > 9?sec:"0"+sec); 
  setTimeout("timerun()",1000); 
}
</script>

<?php
if($checkflag == 0 && $click_count>0)echo '<script>setTimeout("timerun()",1000);</script>';//time running 
if($checkflag == 1){
  echo "<script>alert('You Win!');</script>";
  $user = new User();
  $user->username = $_SESSION['username'];
  $user->best_time = $user->fetch_time($conn);
  $bestlist = explode(":",$_POST["timeshow"]);
  //var_dump($bestlist);
  if($user->best_time > $_POST["timeshow"] || $user->best_time == NULL){
    $user->best_time = $_POST["timeshow"];
    $user->update($conn);
  }
} 
if($checkflag == 2)echo '<script>alert("Game Over!");</script>';
?> 
</body> 
</html>

<!doctype html>
<html>
<head>
<meta charset='UTF-8'><meta name='viewport' content='width=device-width initial-scale=1'>
<title>ScoreBoard</title>
<link href="Css/scoreboard.css" rel="stylesheet" type="text/css" />
<link href="Css/bootstrap.css" rel="stylesheet" type="text/css" />
</head>
<body class='typora-export os-windows'>
<div id='write'  class=''>
<h1 align="center">排行榜</h1>
<div class="row text-center">
    <a href="index.php" class="btn btn-default">返回首页</a>
</div>
<br>
    <figure>
        <table>
            <thead>
                <tr>
                    <th><span>排名</span></th><th><span>用户名</span></th><th><span>最少用时</span></th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("class.php");
                session_start();
                if ($_SESSION['islogin'] !== "1") {
                  header("location:login.php");
                }
                $user = new User();
                $USER = $user->sort($conn);
                $name_arr = $USER[0];
                $time_arr = $USER[1];
                for($i=0;$i<count($name_arr);$i++){
                    $a = $i+1;
                    echo "<tr><td><span>{$a}</span></td><td><span>".$name_arr[$i]."</span></td><td><span>".$time_arr[$i]."</span></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </figure>
    <p>&nbsp;</p>
</div>
</body>
</html>
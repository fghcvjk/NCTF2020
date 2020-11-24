<?php

class User{
    public $username;
    public $password;
    public $time;
    public $best_time;
    public $error = "Usage error!";

    public function __invoke(){
        echo $this->error;
    }

    public function fetch_time($conn){
        $sql="SELECT besttime FROM user WHERE username = '{$this->username}'";
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_assoc($result);
        $time = $row['besttime'];
        return $time;
    }

    public function update($conn){
        $sql="update user set besttime='{$this->best_time}' where username='{$this->username}'";
        mysqli_query($conn,$sql);
        if(mysqli_affected_rows($conn)>0){
            echo "<script>alert('新纪录！');window.location.href='index.php';</script>";
        }else{
            echo "<script>alert('错误');window.location.href='index.php';</script>";
        }
    }

    public function sort($conn){
        $sql="SELECT username,besttime FROM user ORDER BY user.besttime ASC";
        if($conn){
            $query = mysqli_query($conn,$sql);
            $i = 0;
            while($user = mysqli_fetch_array($query)){
                $name_array[$i] = $user['username'];
                $time_array[$i] = $user['besttime'];
                $i++;
            }
        }else{
            die(mysqli_error($conn));
        }
        mysqli_free_result($query);
        $USERS[0] = $name_array;
        $USERS[1] = $time_array;
        return $USERS;
    }
}

class net_test{
    public $url;

    public function __construct($url){
        $this->url = $url;
    }
    public function __toString(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res = curl_exec($ch);
        echo $res;
        curl_close($ch);
    }
    public function __wakeup(){
        $black_list = "/file|3306|base|fil|proc|env/i";
        if(preg_match($black_list, $this->url)) {
            $this->url = "127.0.0.1";
        }
    }
}

class Game{
	public $a;
    public function __destruct() {
		$a = $this->a;
		$a();
    }
}

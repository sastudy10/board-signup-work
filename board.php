<?php
    $dsn = 'mysql:dbname=dbname;host=localhost;charset=utf8';
    $user = 'user';
    $password = 'pass';
    $pdo = new PDO($dsn,$user,$password);

    $sql ="CREATE TABLE IF NOT EXISTS bbox"
        ."("
            ."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
            ."name char(32),"
            ."comment TEXT,"
            ."date  DATETIME,"
            ."nfname char(50)"
        .");";
    $stmt = $pdo->query($sql);

    header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");
    header("Pragma:no-cache");

    $sql = "SELECT COUNT(*) FROM bbox";
    $stmt = $pdo->query($sql);
    $num =  $stmt->fetchColumn();

?>

<?php

  //file
    if($_FILES['up_file']['type'] == "image/png" || $_FILES['up_file']['type'] == "image/jpeg" || $_FILES['up_file']['type'] == "image/gif"|| $_FILES['up_file']['type'] ==""){
        $rand = chr(mt_rand(65,90)) . chr(mt_rand(65,90)) . chr(mt_rand(65,90));
        $nfname = date("YmdGis").$rand;
        $upload = './image/'.$nfname ;
        
        if(move_uploaded_file($_FILES['up_file']['tmp_name'], $upload)){
          chmod( './image/'.$nfname,0644);
	      }else{
        	$nfname = "noimage";
	      }
      }else{
        echo "<p class=errer>画像ファイルを選んでください(ご利用できる形式:jpg,png,gif)</p>";
      }
  //サニタイズ
      if( !empty($_POST) ) {
        foreach( $_POST as $key => $value ) {
          $clean[$key] = htmlspecialchars( $value, ENT_QUOTES);
        }
      }

?>

<?php
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST["name"])){
      if(!empty($_POST["comment"])){
		    if(!empty($nfname)){
 
                $sql = $pdo -> prepare("INSERT INTO bbox (id,name,comment,date,nfname) VALUES (:id,:name,:comment,:date,:nfname)");
                $sql -> bindParam(':id',$id,PDO::PARAM_STR);
                $sql -> bindParam(':name',$name,PDO::PARAM_STR);
                $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
                $sql -> bindParam(':date',$date,PDO::PARAM_STR);
                $sql -> bindParam(':nfname',$nfname,PDO::PARAM_STR);

                $comment = $clean['comment'];
                $name = $clean['name'];
                $date = date('Y/m/d G:i:s');

                $sql -> execute();
        }
      }
    }
  }
?>

<!DOCTYPE html>
<html lang = "ja">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="board.css">
    <title>課題2-簡易掲示板</title>
  </head>
  <body>
 
    <h>簡易掲示板</h>
    <div id=main>
    <form action="board.php" method="post"enctype="multipart/form-data">
      名前：<input class="name" type="text" name="name"  >  
      <input  type="file" name="up_file"><br><hr class="min">
      コメント<br>
      <textarea class="com" name="comment" placeholder="コメント"></textarea><br>
      <input class="btn" type="submit"  value="送信"><br>   
    </form>
    </div>
    
    <?php echo'<p class="cnt">投稿件数:' .$num. '</p>' ?>

    <?php
      $max= 10 ;
      $page = empty($_GET["page"])? 1:$_GET["page"];
      $start = ($page == 1)? 0 : ($page-1) * $max;

      $sql = 'SELECT*FROM bbox ORDER BY id ASC LIMIT '.$start.','.$max;
      $results = $pdo -> query($sql);
      foreach($results as $row){
          echo "<div class='wrapper'>";
          echo "<span class='id'>".$row['id']."</span>";
          echo '<span class="name">'.$row['name'].'</span>';
          echo '<span class="date">'.$row['date'].'</span><br>';
          echo '<p class ="article">'.nl2br($row['comment']).'</p>';
        
	    $url = $row['nfname'];
	    if(strcmp($url,"noimage") == 0){
	    }else{
	      $url = "./image/".$row['nfname'];
	      echo '<div class = links><a href ="'.$url.'">画像リンク</a>あり</div>';
      }
        echo "<hr></div>";
      }

?>

<?php
  $limit = ceil($num/10);	
  $page = empty($_GET["page"])? 1:$_GET["page"];	

  function paging($limit, $page, $disp=5){    
    $next = $page+1;
    $prev = $page-1;
    
    $start =  ($page-floor($disp/2) > 0) ? ($page-floor($disp/2)) : 1;//始点
    $end =  ($start > 1) ? ($page+floor($disp/2)) : $disp;//終点
    $start = ($limit < $end)? $start-($end-$limit):$start;//始点再計算
     echo '<div class ="pages">';
    if($page != 1 ) {
         print '<a href="?page='.$prev.'">&laquo; 前へ</a>';
    }
    
    if($start >= floor($disp/2)){
        print '<a href="?page=1">1</a>';
        if($start > floor($disp/2)) print "..."; 
    }
     
     
    for($i=$start; $i <= $end ; $i++){
         
        $class = ($page == $i) ? ' class="current"':"";
         
        if($i <= $limit && $i > 0 )
            print '<a href="?page='.$i.'"'.$class.'> '.$i.' </a>';
         
    }
       
   
    if($limit > $end){
        if($limit-1 > $end ) print "...";    
        print '<a href="?page='.$limit.'"> '.$limit.' </a>';
    }
         
    if($page < $limit){
        print '<a href="?page='.$next.'">  次へ &raquo;</a>';
    }
     echo'</div>';
     
}
 
paging($limit, $page)
?>


 </body>
</html>

<?php 
//1. 対象のID番号を取得
$id = $_POST["id"];

//2. DB接続します
include("func.php");
$pdo = db_conn();

//３．データ登録SQL作成
$stmt = $pdo->prepare("DELETE FROM gs_bookmark_table WHERE id=:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

//４．データ登録処理後
if($status==false){
    sql_error($stmt);
  }else{
    redirect("select.php");
  }

?>


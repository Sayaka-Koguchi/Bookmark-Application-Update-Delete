<?php 

//1.  POSTデータ取得
$category = $_POST['category']; // カテゴリーを取得
$book_name = $_POST['book_name']; // 書籍名を取得
$book_url = $_POST['book_url']; // URLを取得
$book_comment = $_POST['book_comment']; // コメントを取得
$book_status = $_POST['book_status']; // 読書状況を取得

//2. DB接続ー関数化　
include("func.php"); // 外部ファイルを読み込む
$pdo = db_conn();

//３．データ登録SQL作成

// 1. SQL文を用意
$sql = "INSERT INTO gs_bookmark_table(category,book_name,book_url,book_comment,book_status,date)VALUES(:category,:book_name,:book_url,:book_comment,:book_status,NOW());";
$stmt = $pdo->prepare($sql);

//  2. バインド変数
// Integer 数値の場合 PDO::PARAM_INT
// String文字列の場合 PDO::PARAM_STR

$stmt->bindValue(':category', $category, PDO::PARAM_STR);
$stmt->bindValue(':book_name', $book_name, PDO::PARAM_STR);
$stmt->bindValue(':book_url', $book_url, PDO::PARAM_STR);
$stmt->bindValue(':book_comment', $book_comment, PDO::PARAM_STR);
$stmt->bindValue(':book_status', $book_status, PDO::PARAM_STR);

//  3. 実行
$status = $stmt->execute();

//４．データ登録処理後
if($status === false){
//SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
//関数化
  sql_error($stmt);

  }else{
//５．index.phpへリダイレクト
//関数化
redirect("index.php");

}    

?>
<?php
//1.  DB接続ー関数化
include("func.php"); // 外部ファイルを読み込む
$pdo = db_conn();



//2. ステータスごとのデータカウントSQL作成
$sql_count = "SELECT book_status, COUNT(*) as count FROM gs_bookmark_table GROUP BY book_status";
$stmt_count = $pdo->prepare($sql_count);
$status_count = $stmt_count->execute(); //実行：true or false

// ステータスごとのデータカウント表示
// 関数化
if($status_count == false){
//execute（SQL実行時にエラーがある場合）
sql_error($stmt);
}


// book_statusをカウント
$Statuses = [];
while($result_count = $stmt_count->fetch(PDO::FETCH_ASSOC)) {
  $statuses[$result_count['book_status']] = $result_count['count'];
}
// ステータスが存在しない場合の初期化
$statuses = array_merge(['notStarted' => 0, 'inProgress' => 0, 'completed' => 0], $statuses);


//3．データ取得SQL作成
$sql = "SELECT * FROM gs_bookmark_table";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute(); //実行：true or false

//4．データ表示
if($status == false){
   //execute（SQL実行時にエラーがある場合） 
   //関数化 
    sql_error($stmt);
}

//5. ステータスとカテゴリーの日本語ラベルマッピング
$status_labels = [
  'notStarted' => '未着手',
  'inProgress' => '進行中',
  'completed' => '完了',
];

$category_labels = [
  'literature' => '文学',
  'politics' => '政治',
  'society' => '社会',
  'economy' => '経済',
  'science' => '科学',
  'history' => '歴史',
  'art' => '芸術',
  'Entertainment' => 'エンタメ',
  'others' => 'その他'
];


?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブックマーク表示</title>
    <style>
       body {
        font-family: Arial, sans-serif; /* 全体のフォントをArialに設定 */
    }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* テーブルのレイアウトを固定 */
            
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .book_url {
            width: 20% !important; /* book urlの幅を設定 */
            overflow-wrap: break-word; /* 長いURLを折り返す */
        }
        .book_status{
          font-weight: bold;
          font-size: 18px;
        }


    </style>


</head>
<body id="main">
<header>
    <nav>
      <a href="index.php">ブックマーク</a>
    </nav>
  </header>

  <main>
  <div class="container">
    <h1>ブックマーク一覧</h1>
        <div class="bookmark-list">

      <!-- 読書状況の詳細を表示 -->
        <p class="book_status">＊読書状況一覧＊</p>
        <p>未着手：　<?= $statuses['notStarted']?></p>
        <p>進行中：　<?= $statuses['inProgress']?></p>
        <p>完了：　<?= $statuses['completed']?></p>

        <!-- PHP でデータを取得し、以下の形式で表示する -->
        <table>
            <tr>
                <th>Date</th>
                <th>カテゴリー</th>
                <th>書籍名</th>
                <th class="book_url">URL</th> <!-- クラスを追加 -->
                <th>コメント</th>
                <th>読書状況</th>
                <th>更新</th>
                <th>削除</th>
            </tr>


        <?php while ($result = $stmt->fetch(PDO::FETCH_ASSOC)): ?> 
          <!-- <tr> 
          <td><?= htmlspecialchars($result['date'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($category_labels[$result['category']], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($result['book_name'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="book_url">
          <a href="<?= htmlspecialchars($result['book_url'], ENT_QUOTES, 'UTF-8') ?>
            " target="_blank" rel="noopener noreferrer">
          <?= htmlspecialchars($result['book_url'], ENT_QUOTES, 'UTF-8') ?>
          </a></td>
          <td><?= htmlspecialchars($result['book_comment'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($status_labels[$result['book_status']], ENT_QUOTES, 'UTF-8') ?></td>
          </tr> -->
          <!-- 関数化 -->
          <tr>
            <!-- <td><?= h($result['id']) ?></td>   -->
            <td><?= h($result['date']) ?></td>
            <td><?= h($category_labels[$result['category']]) ?></td>
            <td><?= h($result['book_name']) ?></td>
            <td class="book_url">
                <a href="<?= h($result['book_url']) ?>" target="_blank" rel="noopener noreferrer">
                    <?= h($result['book_url']) ?>
                </a>
            </td>
            
            <td><?= h($result['book_comment']) ?></td>
            <td><?= h($status_labels[$result['book_status']]) ?></td>
            
            <td>
                <form method="GET" action="detail.php" class="action-form">
                    <input type="hidden" name="id" value="<?= h($result['id']) ?>">
                    <input type="submit" value="更新">
                </form>
            </td>
            
            <td>
                <form method="POST" action="delete.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= h($result['id']) ?>">
                    <input type="submit" value="削除" onclick="return confirm('本当に削除しますか？')">
                </form>
            </td>
          </tr>

        <?php endwhile; ?> 

      </table>

       

        </div>

    </div>

  </main>


    
</body>
</html>
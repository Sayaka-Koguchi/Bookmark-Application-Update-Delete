<?php
// 1. クエリパラメータの確認
if (!isset($_GET["id"])) {
    die("IDが指定されていません");
}
$id = $_GET["id"];

// 2. DB接続ー関数化
include("func.php"); // 外部ファイルを読み込む
$pdo = db_conn();

// 3. データ取得SQL作成
$sql = "SELECT * FROM gs_bookmark_table WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT); // Integer（数値）
$status = $stmt->execute(); // 実行：true or false

// 4. データ表示
if ($status == false) {
    // execute（SQL実行時にエラーがある場合）
    sql_error($stmt);
} else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        die("IDに該当するデータが見つかりません");
    }
    $category = $result['category'];
    $book_name = $result['book_name'];
    $book_url = $result['book_url'];
    $book_comment = $result['book_comment'];
    $book_status = $result['book_status'];
}

// 5. ステータスとカテゴリーの日本語ラベルマッピング
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
    <title>ブックマーク</title>
</head>
<header>
    <nav>
        <a href="select.php">ブックマーク表示</a>
    </nav>
</header>
<body>
<form method="POST" action="update.php">
    <fieldset>
        <legend>ブックマーク更新</legend>
        
        カテゴリー：
        <select name="category" id="category">
            <option value="literature" <?= $category == 'literature' ? 'selected' : '' ?>>文学</option>
            <option value="politics" <?= $category == 'politics' ? 'selected' : '' ?>>政治</option>
            <option value="society" <?= $category == 'society' ? 'selected' : '' ?>>社会</option>
            <option value="economy" <?= $category == 'economy' ? 'selected' : '' ?>>経済</option>
            <option value="science" <?= $category == 'science' ? 'selected' : '' ?>>科学</option>
            <option value="history" <?= $category == 'history' ? 'selected' : '' ?>>歴史</option>
            <option value="art" <?= $category == 'art' ? 'selected' : '' ?>>芸術</option>
            <option value="Entertainment" <?= $category == 'Entertainment' ? 'selected' : '' ?>>エンタメ</option>
            <option value="others" <?= $category == 'others' ? 'selected' : '' ?>>その他</option>
        </select><br>
        
        <label for="book_name">書籍名: <input type="text" id="book_name" name="book_name" value="<?= h($book_name) ?>"></label><br>
        <label for="book_url">URL： <input type="text" id="book_url" name="book_url" value="<?= h($book_url) ?>"></label><br>
        <p>コメント：</p>
        <label for="book_comment"><textarea name="book_comment" rows="3" cols="30"><?= h($book_comment) ?></textarea></label><br>
        
        読書状況：
        <select name="book_status" id="book_status">
            <option value="notStarted" <?= $book_status == 'notStarted' ? 'selected' : '' ?>>未着手</option>
            <option value="inProgress" <?= $book_status == 'inProgress' ? 'selected' : '' ?>>進行中</option>
            <option value="completed" <?= $book_status == 'completed' ? 'selected' : '' ?>>完了</option>
        </select><br>
        
        <input type="hidden" name="id" value="<?= $id; ?>">
        <input type="submit" value="更新">
    </fieldset>
</form>
</body>
</html>

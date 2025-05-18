<?php

declare(strict_types=1);

/**
 * @var array<int, array{title: string, descriptions: array<int, string>}> $searchResults
 */
$searchResults = [];
$searchQuery = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $searchQuery = (string)$_POST['query'];
    // ダミーの検索結果
    $dummyData = [
        'PHP' => ['PHP: Hypertext Preprocessor', 'PHP is a popular general-purpose scripting language'],
        'Python' => ['Python is a programming language', 'Python is easy to learn'],
        'JavaScript' => ['JavaScript is a programming language', 'JavaScript is the language of the web']
    ];
    
    foreach ($dummyData as $keyword => $descriptions) {
        if (stripos($keyword, $searchQuery) !== false || 
            stripos(implode(' ', $descriptions), $searchQuery) !== false) {
            $searchResults[] = [
                'title' => $keyword,
                'descriptions' => $descriptions
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索アプリ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-form {
            margin: 20px 0;
        }
        .search-input {
            padding: 8px;
            width: 300px;
        }
        .search-button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-results {
            margin-top: 20px;
        }
        .result-item {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .result-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #2196F3;
        }
    </style>
</head>
<body>
    <h1>プログラミング言語検索</h1>
    
    <form class="search-form" method="POST">
        <input type="text" name="query" class="search-input" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="検索キーワードを入力">
        <button type="submit" class="search-button">検索</button>
    </form>

    <?php if (!empty($searchResults)): ?>
        <div class="search-results">
            <h2>検索結果</h2>
            <?php foreach ($searchResults as $result): ?>
                <div class="result-item">
                    <div class="result-title"><?= htmlspecialchars($result['title']) ?></div>
                    <?php foreach ($result['descriptions'] as $description): ?>
                        <p><?= htmlspecialchars($description) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p>検索結果が見つかりませんでした。</p>
    <?php endif; ?>
</body>
</html> 
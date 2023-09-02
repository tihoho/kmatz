<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/configs/main.php';

use app\helpers\MySQL;
use app\helpers\Utils;

$mysql = (new MySQL())->conn;

$q = <<<SQL
SELECT
    Minute,
    RowsCount,
    AvgContentLength,
    (SELECT MIN(created_at) FROM urls WHERE LEFT(created_at, 16) = Minute) AS FirstSavedAt,
    (SELECT MAX(created_at) FROM urls WHERE LEFT(created_at, 16) = Minute) AS LastSavedAt
FROM (
    SELECT
        LEFT(created_at, 16) AS Minute,
        COUNT(*) AS RowsCount,
        AVG(content_len) AS AvgContentLength
    FROM
        urls
    GROUP BY
        Minute
) AS Subquery
ORDER BY
    Minute;
SQL;

$stmt = $mysql->prepare($q);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo Utils::assocToTable($result);


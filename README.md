
#### 1. Установка
```bash
docker compose up -d
docker exec kma_php composer install
```

#### 2. Миграции
```bash
docker exec kma_php php migrations.php
```

#### 3. Consumer - Запись в БД (можно выйти после запуска [^C], он будет работать в фоне)
```bash
docker exec kma_php php urlsConsumer.php
```

#### 4. Добавление urls в очередь
```bash
docker exec kma_php php urlsToQueue.php
```

#### 5. Метрики 
```bash
docker exec kma_php php showMetrics.php
```

Output:
```
Minute           | RowsCount | AvgContentLength | FirstSavedAt        | LastSavedAt
2023-09-02 04:45 | 1         | 629647.0000      | 2023-09-02 04:45:44 | 2023-09-02 04:45:44
2023-09-02 04:46 | 3         | 238284.3333      | 2023-09-02 04:46:13 | 2023-09-02 04:46:48
2023-09-02 04:47 | 4         | 167431.5000      | 2023-09-02 04:47:04 | 2023-09-02 04:47:51
2023-09-02 04:48 | 2         | 456536.0000      | 2023-09-02 04:48:11 | 2023-09-02 04:48:15
```

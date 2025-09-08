# 勤怠管理アプリ

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:oura-takeshi/flea-market.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

> *MacのM1・M2チップのPCの場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。
エラーが発生する場合は、docker-compose.ymlファイルの「mysql」内に「platform」の項目を追加で記載してください*
``` bash
mysql:
    platform: linux/x86_64(この文追加)
    image: mysql:8.0.26
    environment:
```

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```

##  adminのログイン用初期データ

- メールアドレス: admin@example.com パスワード: admin1234

## userのログイン用初期データ

- メールアドレス: hoge@example.com パスワード: hoge1234

## 使用技術(実行環境)
- PHP7.4.9
- Laravel8.83.8
- MySQL8.0.26

## ER図
![alt](erd.png)

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/

## 承認済み申請について
申請一覧画面（一般ユーザー・管理者）で「承認済み」と表示されている申請の「詳細」を押すと、勤怠詳細画面に移動します。
同じユーザー・同じ日に複数の申請が承認されている場合でも、どの「詳細」を押しても常に最新の承認内容が表示されます。
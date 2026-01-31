# フリーマーケットアプリ
Laravelを使って作成したお問い合わせフリーマーケットアプリです。
画面設計・テーブル仕様書に沿って開発しており、ダミーデータも作成済みです。

## 環境構築
### Dockerビルド
git@github.com:YUKKE1009/fleamarket-app.git
docker-compose up -d --build

### Laravel環境構築
```bash
docker-compose exec php bash
composer install
cp .env.example .env  # 環境変数を適宜変更
php artisan key:generate
php artisan migrate
php artisan db:seed
```

[.envファイル]
```.env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=fleamarket_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

## 環境開発
・お問い合わせ画面：http://localhost/
・ユーザー登録：http://localhost/register
・phpMyAdmin:http://localhost:8080/

## 使用技術（実行環境）
・PHP 8.1.34
・Laravel 8.83.8
・MySQL 8.0
・nginx 1.21.1

## ER図
![ER図](docs/模擬案件(フリーマーケットアプリ)ER図.png)

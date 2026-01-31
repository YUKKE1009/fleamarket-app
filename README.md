# フリーマーケットアプリ
Laravelを使って作成したお問い合わせフリーマーケットアプリです。
画面設計・テーブル仕様書に沿って開発しており、ダミーデータも作成済みです。

## 環境構築
### Dockerビルド
```bash
# リポジトリをクローン
git@github.com:YUKKE1009/fleamarket-app.git
# ディレクトリ移動
cd contact-form
# Dockerビルド＆起動
docker-compose up -d --build
# VSCodeで開く
code .

### Laravel環境構築
```bash
# コンテナ内に入る
docker-compose exec php bash
# 依存パッケージをインストール
composer install
# 環境変数ファイルを作成
cp .env.example .env
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
### Laravel初期化
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## 環境開発
・


## 使用技術（実行環境）
・PHP 8.1.34

・Laravel 8.83.8

・MySQL 8.0

・nginx 1.21.1

## ER図
![ER図](docs/模擬案件(フリーマーケットアプリ)ER図.png)

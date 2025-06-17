# CakePHP Application Skeleton

![Build Status](https://github.com/cakephp/app/actions/workflows/ci.yml/badge.svg?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%207-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

A skeleton for creating applications with [CakePHP](https://cakephp.org) 4.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## 開発環境の立ち上げ
0. 前提
https://www.dropbox.com/scl/fo/m4qax8qs01cxt7hf5odvy/ABdpTA2gytwO3dYX853x8iA?rlkey=jwftf8uoqun7xbfy6fk6f72wp&st=p5xx8uo9&dl=0
上のファイルから、app_loal.php, app.phpをダウンロードして、configフォルダに配置する
.envファイルをプロジェクトルートに作成し、環境変数を設定する

1. 以下のサイトでComposerをインストールする
https://getcomposer.org/download/

2. 以下のコマンドでサーバーを立ち上げる
```bash
bin/cake server -p 8765
```

3. 以下のコマンドでMySQLを立ち上げる
```bash
docker-compose up 
```

4. seed.sqlを(http://localhost:8085/phpmyadmin/)でインポートする

5. migrateを実行する

6. http://localhost:8765/admin/login にログインする


## 管理ユーザー
loginId: esakiryota
password: esaki1217

## 一般ユーザー
http://localhost:8765/admin/usersでユーザーを作成する


## memo
・cacheをクリアにする。databaseを変更した場合は。
コンソールコマンドを使用する:
CakePHPのコンソールコマンドを使用して、キャッシュをクリアできます。コンソールで次のコマンドを実行します：

bash
Copy code
bin/cake cache clear_all
これにより、すべてのキャッシュがクリアされます。

手動でキャッシュを削除する:
キャッシュフォルダ内のキャッシュファイルを手動で削除することもできます。通常、キャッシュフォルダは tmp/cache/ 内にあります。このフォルダ内のすべてのファイルやサブフォルダを削除することで、キャッシュをクリアできます。


getIdentityはキャッシュ保存するため、データを更新した場合はキャッシュをクリアし、ログインを再実行する必要あり。



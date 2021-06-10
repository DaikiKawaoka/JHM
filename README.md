# JHM

## 参考記事

https://yutaro-blog.net/2021/04/30/docker-laravel-vuejs-3/#index_id2  
https://www.seeds-std.co.jp/blog/creators/2019-09-17-171256/


# 開発環境作成手順

1 vscodeを新しいウィンドウで開く。

2 vscodeの上にマウスを持っていき、ターミナルの新しいターミナルを押す。

3 $ cd Desktop

4 $ git clone -b develop https://github.com/DaikiKawaoka/JHM.git

5 デスクトップにあるJHMフォルダをvscodeにドラッグ＆ドロップ

6 vscodeのターミナルが閉じている場合は、2をもう一度し、ターミナルを出す。

7 README.mdと同じ層に .envファイル を作成

8 .env.sampleを参考にしながら .envを編集

9 src/ の中に .envファイル を作成

10 src内の .env.exampleファイルをコピーし、 src/.env ファイルにペースト

11 src/.env の該当コードを下の線の中のように編集 

-------------------------------  
DB_CONNECTION=mysql

DB_HOST=db

DB_PORT=3306

DB_DATABASE=jhm_db

DB_USERNAME=jhm_user

DB_PASSWORD=password

--------------------------------- 

12 $ docker compose build

13 $ docker compose up -d

14 $ docker compose exec app bash

15 $ composer install

16 $ php artisan key:generate

17 $ php artisan config:clear

18 $ php artisan migrate:refresh --seed

18 $ exit



src/vendor/laravel/framework/src/Illuminate/Foundation/Auth/RegistersUsers.php内の

$this->guard()->login($user);

をコメントアウトすること
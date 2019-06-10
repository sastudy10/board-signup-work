<!DOCTYPE html>
<html lang = "ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="signup.css">
    <title>課題1-会員登録画面</title>
  </head>
  <body>
    <div id = "main">
    <h>会員登録画面</h>

    <form action= ""="post">            <<!-- 今回の目的はフォームのバリデーションなので、POST先は省略 -->>
      <p>アカウント名：<input type="text" name="account" value="<?= $_POST['account']?>" required></p>
      <p>パスワード：<input type="password" name="password"  pattern = "^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[&amp;$,._-])[a-zA-Z\d&amp;$,._-]{8,32}$" title="半角英大文字・半角英小文字・半角数字・記号を含む8文字以上32文字以下" required=""></p>
    <input class="btn" type="submit" value="登録">

  </form>
  </div>

  <h2>パスワード入力時の注意</h2>
    <ul>
      <li>8文字以上かつ32文字以下</li>
      <li>半角英大文字、半角英小文字、半角数字、記号を1文字以上含む</li>
      <li>使える記号：<span crass = "a">,</span>・<span crass = "a"> .</span>・<span crass = "a"> _</span>・<span crass = "a">-</span>・<span crass = "a">$</span>・<span crass = "a"> &</span></li>
    </ul>

  </body>
  </html

<form method="post" action="./check.php">
  <?php
    //  入力値の引継ぎ参考URL： https://gray-code.com/php/make-the-form-vol4/
    //　CSRF対策のワンタイムトークン発行    http://localhost/form.php

    $randomNumber = openssl_random_pseudo_bytes(16);
    $token = bin2hex($randomNumber);
    echo '<input name="input_token" type="hidden" value="'.$token.'">';
    //if(!empty($_POST["email_1"]) ){ echo $_POST["email_1"]; }
    //トークンをセッションに格納
    session_start();
    $_SESSION["input_token"] = $token; //グローバル変数らしい    
  ?>
   
 <!DOCTYPE html>  
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <meta name="description" content="大放技イベント登録フォーム" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>大放技登録フォーム</title>
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon.ico">
        <link rel="stylesheet" href="css/style.css" media="all" />
    </head>
    <body>
        <div class="contact">
            <h1 class="contact-ttl">登録フォーム</h1>
         
            <form method="post" action="./check.php">
                <table class="contact-table">
                    <tr>
                        <th class="contact-item">氏　名</th>
                        <td class="contact-body">
                            <input type="text" name="input_text" value="<?php if(!empty($_POST["input_text" ])) { echo htmlspecialchars($_POST["input_text"], ENT_QUOTES, "UTF-8");} ?>"　required="required"  placeholder="必須"　 class="form-text" />           
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">所属施設・学校名</th>
                        <td class="contact-body">
                            <input type="text" name="所属" required="required" placeholder="必須" class="form-text2" value="<?php if(!empty($_POST["所属" ])) { echo $_POST["所属" ]; }?>" />
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">メールアドレス</th>
                        <td class="contact-body">
                            <input id="email_1" name="email_1" type="email" required="required" placeholder="必須" class="form-text" value="<?php if(!empty($_POST["email_1"]) ){ echo $_POST["email_1"]; } ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">メールアドレス<br>（確認用）</th>
                        <td class="contact-body">
                            <input type="email"　id="email_2"　required="required"  placeholder="必須"　name="email_2" class="form-text" oninput="CheckEmail_2(this)" value="<?php if(!empty($_POST["email_1"]) ){ echo $_POST["email_1"]; } ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">参加形態</th>
                        <td class="contact-body">
                            <label class="contact-keitai">
                                <input type="radio" name="keitai" value="Web参加" checked="checked" <?php if( !empty($_POST['keitai']) && $_POST['keitai'] === "Web参加" ){ echo 'checked'; } ?>>
                                <span class="contact-skill-txt">Web参加（会員限定先着80名）</span>
                            </label>
                            <label class="contact-skill">
                                <input type="radio" name="keitai" value="会場参加" <?php if( !empty($_POST['keitai']) && $_POST['keitai'] === "会場参加" ){ echo 'checked'; } ?>/>
                                <span class="contact-skill-txt">会場参加（先着15名)</span>
                            </label>                        
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">区　分</th>
                        <td class="contact-body">
                            <label class="contact-skill">
                                <input type="radio" name="区分" value="大放技会員" checked="checked" onclick="connecttext0('textforscb3',this.checked); "<?php if( !empty($_POST['区分']) && $_POST['区分'] === "大放技会員" ){ echo 'checked'; } ?>/>
                                <span class="contact-skill-txt">大放技会員・協賛会員</span>
                            </label>
                            <label class="contact-skill">
                                <input type="radio" name="区分" value="日放技会員" onclick="connecttext1('textforscb3',this.checked);" <?php if( !empty($_POST['区分']) && $_POST['区分'] === "日放技会員" ){ echo 'checked'; } ?>/>
                                <span class="contact-skill-txt">日放技会員（他府県会員）</span>
                            </label>
                            <label class="contact-skill">
                                <input type="radio" name="区分" value="非会員" onclick="connecttext2('textforscb3',this.checked);" <?php if( !empty($_POST['区分']) && $_POST['区分'] === "非会員" ){ echo 'checked'; } ?> />
                                <span class="contact-skill-txt">非会員（先に参加費をお支払いください）</span>
                            </label>
                            <label class="contact-skill">
                                <input type="radio" name="区分" value="学　生" onclick="connecttext3('textforscb3',this.checked);" <?php if( !empty($_POST['区分']) && $_POST['区分'] === "学　生" ){ echo 'checked'; } ?>/>
                                <span class="contact-skill-txt">学生（社会人院生は除く）</span>
                            </label>
                            <label class="contact-skill">
                                <input type="radio" name="区分" value="一般の方" onclick="connecttext4('textforscb3',this.checked);" <?php if( !empty($_POST['区分']) && $_POST['区分'] === "一般の方" ){ echo 'checked'; } ?>/>
                                <span class="contact-skill-txt">一般の方（府民公開講座のみ無料で参加できます）</span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">日放技番号（技師会員）</th>
                        <td class="contact-body">
                            <input type="number" name="Nナンバー" id ="nn" class="form-text3" value="<?php if( !empty($_POST['Nナンバー']) ){ echo $_POST['Nナンバー']; } ?>" />
                            <font size="2"> 協賛会員は"0"</font>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">大放技番号（大放技会員のみ）</th>
                        <td class="contact-body">
                            <input type="number" name="Dナンバー" id ="dn" class="form-text3" value="<?php if( !empty($_POST['Dナンバー']) ){ echo $_POST['Dナンバー']; } ?>"/>
                            <font size="2"> 協賛会員は"0"</font>
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="contact-item">ブロック名（大放技会員のみ）</th>
                        <td class="contact-body">
                            <select name="ブロック" class="form-select" id ="bn">
                            　　<option value="ブロック名" <?php if( !empty($_POST['ブロック']) && $_POST['ブロック'] === "ブロック名" ){ echo 'selected'; } ?>>ブロック名</option>
                                <option value="中央ブロック" <?php if( !empty($_POST['ブロック']) && $_POST['ブロック'] === "中央ブロック" ){ echo 'selected'; } ?>>中央ブロック</option>
                                <option value="東ブロック" <?php if( !empty($_POST['ブロック']) && $_POST['ブロック'] === "東ブロック" ){ echo 'selected'; } ?>>東ブロック</option>
                                <option value="西ブロック" <?php if( !empty($_POST['ブロック']) && $_POST['ブロック'] === "西ブロック" ){ echo 'selected'; } ?>>西ブロック</option>
                                <option value="南ブロック" <?php if( !empty($_POST['ブロック']) && $_POST['ブロック'] === "南ブロック" ){ echo 'selected'; } ?>>南ブロック</option>
                                <option value="北ブロック" <?php if( !empty($_POST['ブロック']) && $_POST['ブロック'] === "北ブロック" ){ echo 'selected'; } ?>>北ブロック</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">領収書番号（非会員のかた）</th>
                        <td class="contact-body">
                            <input type="number" id ="textforscb3" required="required" name="Rナンバー" class="form-text3" value= "<?php if( !empty($_POST['Rナンバー']) ){ echo $_POST['Rナンバー']; } ?>" disabled="disabled"/>　<font size="2">ハイフンは不要</font>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">備　考</th>
                        <td class="contact-body">
                            <textarea name="備考" class="form-textarea" placeholder="何かご要望やご意見・ご質問等があればご記入ください"><?php if(!empty($_POST["備考"])) { echo $_POST["備考"]; }?></textarea>
                        </td>
                    </tr>
                </table>

                <input class="contact-submit" type="submit" name="submit" value="確　認" />
            </form>
            
            <script language="JavaScript" type="text/javascript">
           // <!--
              function CheckEmail_2(input){
                
                //IE対応の為変更
                //var mail = email_2.value;
                //メールフォームの値を取得
                //var form = document.getElementById("email_1");
                var mail = document.getElementById("email_1").value; //メールフォームの値を取得
                var mailConfirm = input.value; //メール確認用フォームの値を取得(引数input)
 
                //input.setCustomValidity(mail)
                // パスワードの一致確認
                if(mail != mailConfirm){
                  input.setCustomValidity('メールアドレスが一致しません'); // エラーメッセージのセット
                }else{
                  input.setCustomValidity(''); // エラーメッセージのクリア
                }
              }
              function connecttext0(id, ischecked ) {
                  // チェック状態に合わせて有効・無効を切り替える
                  if(ischecked==true){
                      document.getElementById("textforscb3").disabled = true;                  
                      document.getElementById("nn").disabled = false;                 
                      document.getElementById("dn").disabled = false;                 
                      document.getElementById("bn").disabled = false;
                  }
              }
              function connecttext1(id, ischecked ) {
                  // チェック状態に合わせて有効・無効を切り替える
                  if(ischecked==true){
                      document.getElementById("textforscb3").disabled = true;            
                      document.getElementById("nn").disabled = false;                 
                      document.getElementById("dn").disabled = true;                 
                      document.getElementById("bn").disabled = true;
                  }
              }
              function connecttext2(id, ischecked ) {
                  // チェック状態に合わせて有効・無効を切り替える
                  if(ischecked==true){
                      document.getElementById("textforscb3").disabled = false;
                      document.getElementById("nn").disabled = true;                 
                      document.getElementById("dn").disabled = true;                 
                      document.getElementById("bn").disabled = true;
                  }
              }
              function connecttext3(id, ischecked ) {
                  // チェック状態に合わせて有効・無効を切り替える
                  if(ischecked==true){
                      document.getElementById("textforscb3").disabled = true;
                      document.getElementById("nn").disabled = true;                 
                      document.getElementById("dn").disabled = true;                 
                      document.getElementById("bn").disabled = true;
                  }
              }
              function connecttext4(id, ischecked ) {
                  // チェック状態に合わせて有効・無効を切り替える
                  if(ischecked==true){
                      document.getElementById("textforscb3").disabled = true;
                      document.getElementById("nn").disabled = true;                 
                      document.getElementById("dn").disabled = true;                 
                      document.getElementById("bn").disabled = true;
                  }
              }
            // -->
            </script>            
        </div>
    </body>
</html>
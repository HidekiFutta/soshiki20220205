<?php
  ini_set("display_errors", 'On');
  error_reporting(E_ALL);
?>

<?php  
  session_start();
  if(!$_SESSION) {
    echo '送信できませんでした。再度お試しください。';
    echo '詳しくは　"itdrive@daihougi.ne.jp"　までお問い合わせください。';
  }
  
  //参考HP　https://designsupply-web.com/media/programming/1642/
  //任意入力項目の配列が空の場合のエラーメッセージ制御
  error_reporting(E_ALL ^ E_NOTICE);

  require '../vendor/autoload.php';

  //タイムスタンプ
  date_default_timezone_set('Asia/Tokyo');
  $timeStamp = time();
  //$week = array('日', '月', '火', '水', '木', '金', '土');
  $dateFormatYMD = date('Y年m月d日',$timeStamp);
  $dateFormatHIS = date('H時i分s秒',$timeStamp);
  //$weekFormat = "（".$week[date('w',$timeStamp)]."）";
  $outputDate = $dateFormatYMD.$dateFormatHIS;

  //XSS対策用サニタイズ
  function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
  
  //配列データの処理
  //$checkboxArray = implode(",",$_SESSION['スキル']);

  //メール本文内に表示するデータの変数化
  $event = "組織部学術研修会　担当：中央ブロック";
  $count = h($_POST["a"]);
  $text = h($_SESSION['input_text']);
  $kana = h($_SESSION['所属']);
  $emails = h($_SESSION['email_1']);
  $keitai = h($_SESSION['keitai']);
  $tel = h($_SESSION['区分']);
  $url = h($_SESSION['Nナンバー']);
  $zipcode = h($_SESSION['Dナンバー']);
  $radio = h($_SESSION['ブロック']);
  $checkbox = h($_SESSION['Rナンバー']);
  $textarea = h($_SESSION['備考']);
  $ZoomURL = "https://us02web.zoom.us/meeting/register/tZcrfuyqrzosGNfUCK9ImOXkSQs0NousJps-";
  $number =  rtrim($keitai, '参加')."：".$count;
  
  //Web参加と会場参加で案内文を切り分ける：ヒアドキュメント内に表示する文面
  if( $keitai =="Web参加"){
    $announce ="・Web参加の方は次のボタンを押してZoomに登録してください。<br>　　 こちら　⇒　<a href='$ZoomURL'>Zoom登録";}
  else{
    $announce ="・COVID-19の感染状況によりWebのみになった場合は、ご連絡いたします。";
  }

  //自動返信メール本文（ヒアドキュメント）
  $messageUser = <<< EOD
  <html>
  <body>
  <p>{$text}　様</p>
  
  <p>「{$event}」にご登録いただきありがとうございます。<br>
  下記の内容で受付ました。</p>
  
     ---------------------------------------------------------------
  <ul> 
  <li>【受付番号】{$number}</li>
  <li>【氏　名】{$text}</li>
  <li>【施設名】{$kana}</li>
  <li>【メール】{$emails}</li>
  <li>【参加形態】{$keitai}</li>
  <li>【区　分】{$tel}</li>
  <li>【日放技番号】{$url}</li>
  <li>【大放技番号】{$zipcode}</li>
  <li>【ブロック名】{$radio}</li>
  <li>【領収書番号】{$checkbox}</li>
  <li>【備　考】{$textarea}</li>
  </ul>
      ---------------------------------------------------------------
  
  <p>{$announce} </p>
  <p>・参加形態を変更される場合は、あらためて登録しなおしてください。<br>
  　　 参加者数に制限があるため、再登録が必要です。</p>
  <p>・登録の取り消しやご不明な点は<br>
  　　 mail:  itdrive@daihougi.ne.jp<br>
  　までお問い合わせください。</p>
  <p>・また、イベントの内容については<br>
  　　 mail: m-kusumoto@daihougi.ne.jp<br>
  　までお問い合わせください。</p>
  
  </body>
  </html>
EOD;

  //管理者確認用メール本文（ヒアドキュメント）
   $messageAdmin = <<< EOD
HPより以下の登録がありました。

----------------------------------------------------

【受付　番号】{$number}
【氏　　　名】{$text}
【施　設　名】{$kana}
【メ　ー　ル】{$emails}
【参加　形態】{$keitai}
【区　　　分】{$tel}
【日放技番号】{$url}
【大放技番号】{$zipcode}
【ブロック名】{$radio}
【領収書番号】{$checkbox}
【備　　　考】{$textarea}

----------------------------------------------------
EOD;


//メール共通送信設定
//mb_language("ja");
//mb_internal_encoding("UTF-8");

//if(!empty($_SESSION['email_1'])) {

$email = new \SendGrid\Mail\Mail();
    $email->setFrom("fujita@daihougi.ne.jp", "大放技");
    $email->setSubject("大放技イベント受付");
    $email->addTo($emails, "User");
    $email->addContent("text/html", $messageUser);
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
    try {
      $response = $sendgrid->send($email);
      //print $response->statusCode() . "\n";
      //print_r($response->headers());
      //print $response->body() . "\n";
    } catch (Exception $e) {
      echo 'Caught exception: '. $e->getMessage() ."\n";
  }

$email = new \SendGrid\Mail\Mail();
  $email->setFrom("fujita@daihougi.ne.jp", "大放技");
  $email->setSubject("大放技イベント受付");
  $email->addTo("hima71f@yahoo.co.jp", "User");
  $email->addTo("hima71f@gmail.com", "User");
  $email->addContent("text/plain", $messageAdmin);
  $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
  try {
    $response = $sendgrid->send($email);
    //print $response->statusCode() . "\n";
    //print_r($response->headers());
    //print $response->body() . "\n";
  } catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
    
$isSend = true;
  //} else {
   // $isSend = false;
  //}
  session_destroy();

?>

<?php if($isSend):
  //受付番号：カウントアップ
  $conn = "host=ec2-3-230-219-251.compute-1.amazonaws.com port=5432 dbname=d7mmugf289n5ol user=wwdgwynyievckz password=bac3249f96249a528949d80f7d095405efcb6e5f9898d60798a38ce155c03017";
    
  $link = pg_connect($conn);
  if (!$link) {
      die('接続失敗です。'.pg_last_error());
  }
  // タイムゾーンの初期化と日付の取得
  date_default_timezone_set('Asia/Tokyo');

  //pg_set_client_encoding($conn, "sjis");
  
  $result = pg_query('SELECT id, count, web FROM sanka');
  if (!$result) {
      die('クエリーが失敗しました。'.pg_last_error());
  } 
  for ($i = 0 ; $i < pg_num_rows($result) ; $i++){
      $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
  }
  if ($keitai=="会場参加"){
    $rows = $rows['count'] + 1;
    pg_query($link, "UPDATE sanka SET count= $rows WHERE id = '1'"); 
  } else{
    $rows = $rows['web'] + 1;
    pg_query($link, "UPDATE sanka SET web= $rows WHERE id = '1'"); 
  }
  //参加者名簿
  $result2 = pg_query('SELECT * FROM meibo');
  if (!$result2) {
      die('クエリーが失敗しました。'.pg_last_error());
  } 
  $b = pg_num_rows($result2); // 行数確認
  $b = $b+1;
  //insert
  $sql = "INSERT INTO meibo 
  VALUES ($b,$count,'$outputDate','$text','$kana','$emails','$keitai','$tel','$url','$zipcode','$radio','$checkbox','$textarea')";

  $result2_flag = pg_query($link,$sql);
  $close_flag = pg_close($link); 
?>

<!DOCTYPE html>  
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
  <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Last-Modified" content="Fri, 3 Dec 2021 04:52:01 GMT">
  <meta http-equiv="Expires" content="Fri, 3 Dec 2021 04:52:06 GMT">

	<title>大放技セミナー申込み完了フォーム</title>
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="/favicon.ico">
</head>
<body>
		<div align="center">
			<table cool="" gridx="16" gridy="16" showgridx="" showgridy="" usegridx="" usegridy="" width="630" height="357" cellspacing="0" cellpadding="0" border="0">
				<tbody><tr height="16">
					<td colspan="3" width="629" height="16"></td>
					<td width="1" height="16"><spacer type="block" width="1" height="16"></spacer></td>
				</tr>
				<tr height="340">
					<td width="31" height="340"></td>
					<td content="" csheight="340" xpos="31" width="566" valign="top" height="340">
						<div align="center">
							<p><br>
								<font size="3">大放技イベントへのお申込み，ありがとうございました．<br>
								</font></p>
							<p><font size="3">受付内容を【<?php echo h($_SESSION['email_1']); ?>】まで<br>
              お送りましたので，内容をご確認下さい．
              
              </font></p>
							<p></p>
							<p>なお，確認メールが届かない場合は，下記までご連絡下さい．<br>
							</p>
							<p><br>
								<br>
								
								お問い合わせ先<br>
								<br>
								　　(公社)大阪府診療放射線技師会　IT推進委員会<br>
								　　 　Mail：itdrive@daihougi.ne.jp </a><br>
								<br>
								<br>
							</p>
							<p><br>
								<a href="http://www.daihougi.ne.jp/" target="_top"><img src="http://www.daihougi.ne.jp/top_images/oartt_white.gif" alt="" width="137" height="27" border="0"></a></p>
						</div>
					</td>
					<td width="32" height="340"></td>
					<td width="1" height="340"><spacer type="block" width="1" height="340"></spacer></td>
				</tr>
				<tr cntrlrow="" height="1">
					<td width="31" height="1"><spacer type="block" width="31" height="1"></spacer></td>
					<td width="566" height="1"><spacer type="block" width="566" height="1"></spacer></td>
					<td width="32" height="1"><spacer type="block" width="32" height="1"></spacer></td>
					<td width="1" height="1"></td>
				</tr>
			</tbody></table>
		</div>
  </body>
</html>

<?php else: ?> 
  <p>送信エラー：メールフォームからの送信に失敗しました。お手数ですが再度お試しください。 
  </p>
<?php endif; ?>
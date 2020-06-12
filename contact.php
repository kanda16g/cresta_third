<?php
    session_start();
    $errmessage01 = array();
    $errmessage02 = array();
    $errmessage03 = array();
    $errmessage04 = array();
    $errmessage05 = array();
    $mode = 'input';
    if(isset($_POST['back']) && $_POST['back']) {
        // 何もしない
    } else if(isset($_POST['confirm']) && $_POST['confirm']) {
        // お問い合わせ種別
        $_SESSION['cate'] = $_POST['cate'];

        // 会社名・団体名
        if(!$_POST['groupname']) {
            //何もしない
        } else if(mb_strlen($_POST['groupname']) > 100) {
            $errmessage01[] = "*会社名・団体名は100文字以内で入力してください";
        }
        $_SESSION['groupname'] = htmlspecialchars($_POST['groupname'], ENT_QUOTES);

        // お名前
        if(!$_POST['fullname']) {
            $errmessage02[] = "*お名前を入力してください"; 
        } else if(mb_strlen($_POST['fullname']) > 100) {
            $errmessage02[] = "*お名前は100文字以内で入力してください";
        }
        $_SESSION['fullname'] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);

        // メールアドレス
        if(!$_POST['email']) {
            $errmessage03[] = "*メールアドレスを入力してください"; 
        } else if(mb_strlen($_POST['email']) > 200) {
            $errmessage03[] = "*メールアドレスは200文字以内で入力してください";
        } else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errmessage03[] = "*メールアドレスに使用できない文字が含まれています";
        }
        $_SESSION['email'] = htmlspecialchars($_POST['email'], ENT_QUOTES);

        // 電話番号
        if(!$_POST['tel']) {
            //何もしない
        } else if(mb_strlen($_POST['tel']) > 20) {
            $errmessage04[] = "*電話番号は20字以内で入力してください";
        } else if(!filter_var($_POST['tel'], FILTER_SANITIZE_NUMBER_INT)) {
            $errmessage04[] = "*電話番号は半角の数値を入力してください";
        }
        $_SESSION['tel'] = htmlspecialchars($_POST['tel'], ENT_QUOTES);

        // お問い合わせ内容
        if(!$_POST['message']) {
            $errmessage05[] = "*お問い合わせ内容を入力してください"; 
        } else if(mb_strlen($_POST['message']) > 1000) {
            $errmessage05[] = "*お問い合わせ内容は1000文字以内で入力してください";
        }
        $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

        // 同意のチェック
        if(!$_POST['agree']) {
            $errmessage07[] = "*個人情報の取り扱いについて内容を確認の上、チェックを入れてください。";
        }

        // 入力不備があるとき
        if($errmessage01 || $errmessage02 || $errmessage03 || $errmessage04 || $errmessage05 || $errmessage07) {
            $mode = 'input';
        } else {
            $token = bin2hex(random_bytes(32)); // トークン生成
            $_SESSION['token'] = $token;

            $mode = 'confirm';
        }
       
    } else if(isset($_POST['send']) && $_POST['send']) {
        // トークン確認
        if(!$_POST['token'] || !$_SESSION['token'] || !$_SESSION['email']) {
            $errmessage06[] = "*不正な処理が行われました";
            $_SESSION = array();
            $mode = 'input';
        } else if($_POST['token'] != $_SESSION['token']) {
            $errmessage06[] = "*不正な処理が行われました";
            $_SESSION = array();
            $mode = 'input';
        } else {
        // メール文面
        $message = "お問い合わせを受け付けました \n\r"
        . "お問い合わせ種別:" . $_SESSION['cate'] . "\r\n"
        . "会社名・団体名:" . $_SESSION['groupname'] . "\r\n"
        . "お名前:" . $_SESSION['fullname'] . "\r\n"
        . "メールアドレス:" . $_SESSION['email'] . "\r\n"
        . "電話番号:" . $_SESSION['tel'] . "\r\n"
        . "お問い合わせ内容:\r\n"
        . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
        mail($_SESSION['email'], 'お問い合わせありがとうございます', $message);
        mail('shentianpingcheng@gmail.com', 'お問い合わせありがとうございます', $message);
        $_SESSION = array();
        $mode = 'send';
        }

        
    } else {
        $_SESSION['cate'] = "";
        $_SESSION['groupname'] = "";
        $_SESSION['fullname'] = "";
        $_SESSION['email'] = "";
        $_SESSION['tel'] = "";
        $_SESSION['message'] = "";
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>DIGSMILE INC. | お問い合わせ</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;800&family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.5.0.min.js"></script>
    <script src="js/script.js"></script>
</head>
<body>
    <div class="contact common">
        <header class="header">
            <p class="header__title"><a href="index.html">DIGSMILE<span> INC.</span></a></p>
        </header><!-- header -->
        <a class="hammenu" href="#"><span></span><span></span><span></span>MENU</a>
        <nav class="glonav">
            <ul class="glonav__list">
                <li class="glonav__listitem"><a href="about.html">ABOUT US</a></li>
                <li class="glonav__listitem"><a href="#">WORKS</a></li>
                <li class="glonav__listitem"><a href="#">CULTURE</a></li>
                <li class="glonav__listitem"><a href="#">TOPICS</a></li>
                <li class="glonav__listitem"><a href="contact.php">CONTACT</a></li>
            </ul>
        </nav><!-- glonav -->
        <div class="bg"></div>
        <div class="key">
            <div class="key__inner">
                <h1 class="key__txt"><span class="title">CONTACT</span>お問い合わせ</h1>
            </div>
        </div><!-- key -->
        <main>
            <div class="sec">
            <?php if($mode == 'input') { ?>

                 <!-- 入力画面 -->
                 <form action="contact.php" method="post" name="form">
                    <?php 
                        if($errmessage06) {
                            echo '<h2 class="err__title">';
                            echo implode($errmessage05);
                            echo '</h2>';
                        }
                    ?>
                    <p class="contact__txt">ご依頼やご相談についてのご質問やご要望がございましたら、下記フォームよりお気軽にお問い合わせください。送付いただいた内容を確認の上、担当者からご連絡させていただきます。</p>
                    <div class="radiobtn">
                        <p class="radiotitle">お問い合わせ種別<span class="must">必須</span></p>
                        <p class="radioitem"><input id="production" type="radio" name="cate" value="制作依頼" checked><label for="production">制作依頼</label></p>
                        <p class="radioitem"><input id="recruit" type="radio" name="cate" value="採用"><label for="recruit">採用</label></p>
                        <p class="radioitem"><input id="ir" type="radio" name="cate" value="IR"><label for="ir">IR</label></p>
                        <p class="radioitem"><input id="other" type="radio" name="cate" value="その他"><label for="other">その他</label></p>
                    </div>
                    <div class="i-txt">
                        <label for="groupname">会社名・団体名</label>
                        <input id="groupname" type="text" name="groupname" value="<?php echo $_SESSION['groupname'] ?>">
                        <?php 
                            if($errmessage01) {
                                echo '<p class="err"">';
                                echo implode($errmessage01);
                                echo '</p>';
                            }
                        ?>
                    </div>
                    <div class="i-txt">
                        <label for="fullname">お名前</label><span class="must">必須</span>
                        <input id="fullname" type="text" name="fullname" value="<?php echo $_SESSION['fullname'] ?>">
                        <?php 
                            if($errmessage02) {
                                echo '<p class="err">';
                                echo implode($errmessage02);
                                echo '</p>';
                            }
                        ?>
                    </div>
                    <div class="i-txt">
                        <label for="email">メールアドレス</label><span class="must">必須</span>
                        <input id="email" type="email" name="email" value="<?php echo $_SESSION['email'] ?>">
                        <?php 
                            if($errmessage03) {
                                echo '<p class="err">';
                                echo implode($errmessage03);
                                echo '</p>';
                            }
                        ?>
                    </div>
                    <div class="i-txt">
                        <label for="tel">電話番号</label>
                        <input id="tel" type="tel" name="tel" value="<?php echo $_SESSION['tel'] ?>">
                        <?php 
                            if($errmessage04) {
                                echo '<p class="err">';
                                echo implode($errmessage04);
                                echo '</p>';
                            }
                        ?>
                    </div>
                    <div class="txtarea">
                        <label for="message">お問い合わせ内容</label><span class="must">必須</span>
                        <textarea name="message" id="message" cols="30" rows="10"><?php echo $_SESSION['message'] ?></textarea>
                        <?php 
                            if($errmessage05) {
                                echo '<p class="err">';
                                echo implode($errmessage05);
                                echo '</p>';
                            }
                        ?>
                    </div>
                    <div class="privacy">
                        <p class="privacy__title">PRIVACY POLICY</p>
                        <div class="privacy__body">
                            個人情報保護方針<br>
                            株式会社DIGSMILE（以下、当社）では、個人情報の重要性を認識し、以下の基準に従って、適切な管理、保護に努めます。<br><br>
    
                            1.個人情報の収集、利用<br>
                            当社では、お客様との取引、サービスの提供のために個人情報を適法に収集し、収集した目的の範囲内で、個人情報を利用いたします。<br><br>
    
                            2.個人情報の第三者への開示と提供<br>
                            収集したお客様の個人情報は、法的な要請等によらない限り、お客様の承諾を得ない第三者に対して提供・開示はいたしません。<br>
                            業務の都合上、業務委託先に個人情報を提供する場合は、機密保持契約等によって業務委託先に個人情報保護を義務付けるとともに、業務委託先が適切に個人情報を取り扱うように管理いたします。<br><br>
    
                            3.個人情報の保護<br>
                            当社では、個人情報の紛失、破壊、改ざん、不正アクセス、および漏えい等を防止するため、適切な対策を行います。<br><br>
    
                            4.法令および関連規範の遵守<br>
                            当社は、個人情報の取り扱いに関して、個人情報保護法をはじめとする個人情報に関する法令および関連する規範を遵守します。<br><br>
    
                            5.個人情報の開示・訂正・削除<br>
                            当社では、個人情報の開示・訂正・削除等の依頼があった場合、情報提供者本人であることを確認の上、すみやかに対応いたします。<br><br>
    
                            法令や当社方針により、プライバシー・ポリシーを予告なく改訂することがあります。<br><br>
    
                            お問い合わせ窓口<br>
                            当社の個人情報の取扱いに関するお問い合わせは下記までご連絡お願いいたします。<br>
                            株式会社ファイアープレイス<br>
                            044-589-4333
                        </div>
                    </div>
                    <div class="checkagree">
                        <input id="agree" type="checkbox" name="agree" value="agree"><label for="agree">個人情報の取り扱いについて同意のうえ送信します。</label>
                        <?php 
                            if($errmessage07) {
                                echo '<p class="err">';
                                echo implode($errmessage07);
                                echo '</p>';
                            }
                        ?>
                    </div>
                    <div class="submitbtn"><input type="submit" name="confirm" value="確認"></div>
                </form>

            <?php } else if($mode == 'confirm') { ?>

            <!-- 確認画面 -->
                <h2 class="confirm__title">お問い合わせ 内容確認</h2>
                <p class="contact__txt">お問い合わせ内容はこちらでよろしいでしょうか？<br>よろしければ「送信」ボタンを押してください。</p>
                <form action="contact.php" method="post" name="form">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                    <div class="radiobtn">
                        <p class="radiotitle">お問い合わせ種別<span class="must">必須</span></p>
                        <p><?php echo $_SESSION['cate'] ?></p>
                    </div>
                    <div class="i-txt">
                        <label for="groupname">会社名・団体名</label>
                        <p><?php echo $_SESSION['groupname'] ?></p>
                    </div>
                    <div class="i-txt">
                        <label for="fullname">お名前</label><span class="must">必須</span>
                        <p><?php echo $_SESSION['fullname'] ?></p>
                    </div>
                    <div class="i-txt">
                        <label for="email">メールアドレス</label><span class="must">必須</span>
                        <p><?php echo $_SESSION['email']?></p>
                    </div>
                    <div class="i-txt">
                        <label for="tel">電話番号</label>
                        <p><?php echo $_SESSION['tel'] ?></p>
                    </div>
                    <div class="txtarea">
                        <label for="message">お問い合わせ内容</label><span class="must">必須</span>
                        <p><?php echo nl2br($_SESSION['message']) ?></p>
                    </div>
                    
                    <div class="btns">
                        <div class="submitbtn submitbtn--l"><input type="submit" name="back" value="戻る"></div>
                        <div class="submitbtn"><input type="submit" name="send" value="送信"></div>
                    </div>
                </form>

            <?php } else { ?>
            <!-- 完了画面 -->
                <h2 class="done__title">お問い合わせ 送信完了</h2>
                <p class="contact__txt">この度は、お問い合わせいただき、ありがとうございました。<br>
                内容を確認のうえ、改めてご連絡させていただきます。よろしくお願い申し上げます。</p>
            <?php } ?>

            </div><!-- sec -->
        </main><!-- main -->
        <footer class="footer">
            <p class="footer__copy"><small>&copy;2018 DIGSMILE INC.</small></p>
        </footer><!-- footer -->
    </div>
</body>
</html>
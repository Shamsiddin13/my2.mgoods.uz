<?php
session_start();
$pixel_id = isset($_GET['pixel_id']) ? htmlspecialchars($_GET['pixel_id']) : 'default_pixel_id';

// Clear pixel_id from session after use
unset($_SESSION['pixel_id']);
?>

<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyurtma qabul qilindi!</title>
    <!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?php echo $pixel_id; ?>>');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=<?php echo $pixel_id; ?>&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    <style>
        body {
            background: url() repeat scroll 0 0 rgba(0, 0, 0, 0);
            color: #313E47;
            font-family: Arial, sans-serif;
            font-size: 15px;
            height: 100%;
            line-height: 1.5;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        html {
            height: 100%;
        }
        ol, ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        h2 {
            color: #313E47;
            font-size: 36px;
            font-weight: 700;
            line-height: 44px;
            text-align: center;
            text-transform: uppercase;
        }
        h3 {
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            margin: 20px 0;
        }
        a {
            color: #69B9FF;
            text-decoration: none;
        }
        a:hover {
            color: #E14740;
        }
        .order_number {
            color: #424242;
            font-family: Arial, sans-serif;
            font-size: 30px;
            line-height: 38px;
            text-align: center;
            text-transform: uppercase;
            margin: 25px 0;
        }
        .url_more_info {
            display: block;
            font-size: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .block_success {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 30px;
        }
        .list_info {
            display: inline-block;
            text-align: left;
        }
        .list_info li {
            margin: 11px 0;
        }
        .list_info li span {
            display: inline-block;
            font-style: normal;
            font-weight: 700;
            width: 150px;
        }
        .fail {
            text-align: center;
            margin: 25px 0 50px;
        }
        .email {
            margin-top: 40px;
            position: relative;
            text-align: center;
        }
        .error {
            color: #CA3F3F;
            display: none;
            position: absolute;
            top: -28px;
        }
        .mail_block {
            display: inline-block;
        }
        .email input {
            border: 1px solid #B6B6B6;
            border-radius: 5px;
            font-size: 14px;
            height: 30px;
            margin-bottom: 10px;
            outline: none;
            padding-left: 10px;
            padding-right: 10px;
            width: 100%;
            max-width: 200px;
            box-sizing: border-box;
        }
        .button {
            background: rgba(0, 0, 0, 0);
            border: 1px solid #0076A3;
            border-radius: .5em;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            color: #D9EEF7;
            cursor: pointer;
            display: inline-block;
            font: 15px/100% Arial, sans-serif;
            outline: none;
            text-align: center;
            text-decoration: none;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
            padding: .55em 2em .6em;
        }
        .button:hover {
            background: rgba(0, 0, 0, 0);
            color: #D9EEF7;
            text-decoration: none;
        }
        .button:active {
            background: rgba(0, 0, 0, 0);
            color: #80BED6;
            position: relative;
            top: 1px;
        }
        .disabled {
            background: rgba(0, 0, 0, 0);
            color: #80BED6;
        }
        .disabled:hover {
            background: rgba(0, 0, 0, 0);
            color: #80BED6;
            cursor: default;
        }
        .disabled:active {
            top: 0;
        }
        .mail_s {
            color: green;
            position: inherit;
            top: 0;
        }
        .success, .wrap_list_info {
            text-align: center;
        }
        .container {
            max-width: 480px;
            margin: 0 auto;
            overflow: hidden;
            padding: 15px;
        }
        .form-block {
            border-radius: 16px;
            background-color: #f3f3f3;
            padding: 10px;
            margin-top: 5px;
        }
        .error {
            color: #FF0000;
        }
        .alert {
            margin: 15px 0;
            padding: 15px 20px;
            text-align: center;
        }
        .alert-danger {
            border: 1px solid #FF0000;
            background-color: #FFFCFC;
            color: #FF0000;
        }
        .alert-danger a {
            color: #FF0000;
            text-decoration: none;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
        }
        .row .card {
            border: solid thin #35a76e;
            width: 100%;
            margin: 5px 0;
            border-radius: 3px;
            padding: 10px;
        }
        .row .card a {
            color: #35a76e;
            text-decoration: none;
        }
        @media (min-width: 480px) {
            .row .card {
                width: 48%;
                margin: 1%;
            }
        }
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 100%;
            height: auto;
        }
        .accept-img {
            margin-top:30px;
        }
    </style>
</head>
<body>
    <center><img class='accept-img' align='center' src='mark.png' width="64" height="64"></center>
    <div class='wrap_block_success'>
        <div class='block_success'>
            <h2>Buyurtmangiz bizga kelib tushgan!</h2>
            <p class='success' style="font-size:18px; font-weight: 700;">78 113 99 94 raqamidan sizga qo‘ng‘iroq qilingan bolishi mumkin!</p>
            <h1 style='color:#FF0000; text-align: center;'>Iltimos, operator qo‘ng‘irog‘ini kuting.</h1>
        </div>
        <div>
            <a style="color: white;" href="https://www.uz">Buy</a>        
        </div>
    </div>
</body>
</html>

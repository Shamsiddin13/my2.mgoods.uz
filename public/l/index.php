<?php
global $conn_products;
require 'config_db.php';

// Get the unique_link from the query string (provided by the .htaccess rewrite rule)
$unique_link = isset($_GET['unique_link']) ? $_GET['unique_link'] : null;

if ($unique_link) {
    // Fetch landing details
    $landing_query = "SELECT * FROM landing WHERE article = ?";
    $stmt = $conn_products->prepare($landing_query);
    $stmt->bind_param("s", $unique_link);
    $stmt->execute();
    $landing_result = $stmt->get_result();
    $landing = $landing_result->fetch_assoc();

    if (!empty($landing)) {
        // Fetch product details
        $product_query = "SELECT * FROM products WHERE store = ? AND article = ?";
        $stmt = $conn_products->prepare($product_query);
        $stmt->bind_param("ss", $landing['store'], $landing['article']);
        $stmt->execute();
        $product_result = $stmt->get_result();
        $product = $product_result->fetch_assoc();
    } else {
        // If landing not found, check the stream table
        $stream_query = "SELECT * FROM stream WHERE link = ?";
        $stmt = $conn_products->prepare($stream_query);
        $stmt->bind_param("s", $unique_link);
        $stmt->execute();
        $stream_result = $stmt->get_result();
        $stream = $stream_result->fetch_assoc();

        if (!empty($stream)) {
            // Fetch landing details using landing_id from stream table
            $landing_query = "SELECT * FROM landing WHERE id = ?";
            $stmt = $conn_products->prepare($landing_query);
            $stmt->bind_param("i", $stream['landing_id']);
            $stmt->execute();
            $landing_result = $stmt->get_result();
            $landing = $landing_result->fetch_assoc();

            if (!empty($landing)) {
                // Fetch product details
                $product_query = "SELECT * FROM products WHERE store = ? AND article = ?";
                $stmt = $conn_products->prepare($product_query);
                $stmt->bind_param("ss", $landing['store'], $landing['article']);
                $stmt->execute();
                $product_result = $stmt->get_result();
                $product = $product_result->fetch_assoc();
            }
        }
    }

    if (empty($landing)) {
        // Handle case where landing or stream is not found
        http_response_code(404);
        echo "Landing page not found.";
        exit;
    }

    // Close the statement and the connection (optional)
    $stmt->close();
    $conn_products->close();
} else {
    // If unique_link is missing
    http_response_code(400);
    echo "No landing link provided.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="ru-RU">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=480">
    <title><?php echo htmlspecialchars($landing['title']); ?></title>
    <base href="http://127.0.0.1:8000/l/">
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="Montserrat.css">
    <link rel="stylesheet" href="styles.css">

    <style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    /* Error message styling */
    .error-message {
        display: block;
        color: #d9534f;
        /* Red color for errors */
        margin-bottom: 10px;
        /* Increased space below the error message */
        font-size: 14px;
        /* Slightly smaller font size */
        font-weight: bold;
        /* Bold text for emphasis */
        padding: 5px 10px;
        /* Padding around the text */
        border-radius: 4px;
        /* Rounded corners */
        width: auto;
        /* Auto width */
        max-width: 100%;
        /* Ensure it doesn't exceed the width of the container */
        box-sizing: border-box;
        /* Ensure padding and border are included in the width */
    }

    /* Input field styling */
    .field {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        /* Space between input and error message */
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    /* Form group styling */
    .form-group {
        position: relative;
        margin-bottom: 20px;
        /* Increased space between form groups */
    }
    </style>

    <!-- Meta Pixel Code -->
    <script>
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '<?php echo htmlspecialchars($stream['pixel_id']); ?>');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=<?php echo htmlspecialchars($stream['pixel_id']); ?>&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
</head>

<body>
    <div class="main_wrapper">
        <header class="offer_section offer3">
            <h1 class="main_title"><?php echo htmlspecialchars($landing['title']); ?></h1>
            <div class="info_block">
                <p class="subtitle"><?php echo htmlspecialchars($landing['subtitle']); ?></p>
                <img src="<?php echo htmlspecialchars($landing['img1']); ?>" alt="High-Power Cleaner">
            </div>
            <div class="price_block">
                <div class="price_item old">
                    <div class="text">Yetkazib berish hududga qarab 25.000 dan boshlanadi</div>
                </div>
                <div class="price_item new">
                    <div class="text"></div>
                    <div class="value"><span
                            class="price_only7126"><?php echo number_format($product['salePrice'], 0, '.', ' '); ?>
                            so'm</span></div>
                </div>
            </div>
            <div class="benefits_block clearfix">
                <div class="benefit_item">
                    <img src="<?php echo htmlspecialchars($landing['img2']); ?>" alt="High-Power Cleaner">
                </div>
                <div class="benefit_item">
                    <img src="<?php echo htmlspecialchars($landing['img3']); ?>" alt="High-Power Cleaner">
                </div>
                <div class="benefit_item">
                    <img src="<?php echo htmlspecialchars($landing['img4']); ?>" alt="High-Power Cleaner">
                </div>
            </div>
        </header>
        <section class="use_section">
            <div style="text-align: center; padding: 7px 15px 15px 15px;">
                <p style="font-weight: 500; font-size: 18px;"><?php echo htmlspecialchars($landing['description']); ?>
                </p>
            </div>
            <ul class="list2 marker1">
                <li><?php echo htmlspecialchars($landing['text1']); ?></li>
                <li><?php echo htmlspecialchars($landing['text2']); ?></li>
                <?php if (!empty($landing['text3'])): ?>
                <li><?php echo htmlspecialchars($landing['text3']); ?></li>
                <?php endif; ?>
                <li><b>Oldindan hech qanday to'lov yoq ✅</b></li>
                <li><b>Yetkazib berish hududga qarab 25.000 dan boshlanadi 🚚</b></li>
            </ul>
        </section>
        <section class="offer_section offer3 order">
            <form class="main-order-form order_form" id="order1" action="send.php" method="post">
                <input type="hidden" name="source" value="<?php echo htmlspecialchars($stream['source']); ?>">
                <input type="hidden" name="store" value="<?php echo htmlspecialchars($landing['store']); ?>">
                <input type="hidden" name="article" value="<?php echo htmlspecialchars($landing['article']); ?>">
                <input type="hidden" name="pixel_id" value="<?php echo htmlspecialchars($stream['pixel_id']); ?>">
                <select name="region" class="field" required title="'Ҳудудни танланг' bo‘sh bo‘lмаслиги керак.">
                    <option disabled selected hidden>Ҳудудни танланг</option>
                    <option>Андижон</option>
                    <option>Бухоро</option>
                    <option>Жиззах</option>
                    <option>Қорақалпоғистон</option>
                    <option>Қашқадарё</option>
                    <option>Навоий</option>
                    <option>Наманган</option>
                    <option>Самарқанд</option>
                    <option>Сурхандарё</option>
                    <option>Сирдарё</option>
                    <option>Тошкент</option>
                    <option>Тошкент вилояти</option>
                    <option>Фарғона</option>
                    <option>Хоразм</option>
                </select>
                <div class="form-group">
                    <label for="name" class="error-message"></label>
                    <input class="field" type="text" id="name" name="name" placeholder="Ism va Familiya" value="">
                </div>
                <div class="form-group">
                    <label for="phone" class="error-message"></label>
                    <input class="field" type="tel" id="id_phone_number" name="phone" placeholder="Telefon raqam"
                        value="" </div>
                    <button class="button">Buyurtma berish</button>
            </form>
            <br>
        </section>
        <footer class="footer_section">
            <div style="margin:15px 0; text-align:center;">
                <span style="font-size: 22px; font-weight: bold;">Tezkor aloqa</span><br>
                <span style="font-size: 18px; font-weight: bold;">Telefon raqam: <a style="font-size: 15px;"
                        href="tel:+998781139994">78 113 99-94</a></span><br>
                <span style="font-size: 18px; font-weight: bold;">Telegram: <a style="font-size: 15px;"
                        href="https://t.me/mgoodsuz">@Mgoodsuz</a></span><br>
            </div>
        </footer>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script>
    $(document).ready(function() {
        $('#id_phone_number').inputmask("+\\9\\98(99) 999-99-99");
    });
    </script>

    <script src="jquery.min.js"></script>
    <script src="scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
    $(document).ready(function() {
        // Clear error messages on input focus
        $("input, select").on("focus", function() {
            $(this).prev("label.error-message").html("");
        });

        // Clear error messages on page refresh
        $("input, select").each(function() {
            $(this).prev("label.error-message").html("");
        });

        $("#name").inputmask("Regex", {
            regex: "[^0-9]*"
        });

        $("#phone", "#order1")
            .keydown(function(e) {
                if (e.which === 8) {
                    if ($(this).val().length === 3) {
                        e.preventDefault();
                    }
                }
            })
            .bind("focus click", function() {
                $phone = $(this);

                if ($phone.val().length === 0) {
                    $phone.val("998" + $phone.val());
                } else {
                    var val = $phone.val();
                    $phone.val("").val(val); // Ensure cursor remains at the end
                }
            })

            .blur(function() {
                $phone = $(this);

                if ($phone.val() === "(") {
                    $phone.val("");
                }
            });

        $("#order1").validate({
            errorPlacement: function(error, element) {
                element.prev("label.error-message").html(error);
            },
            rules: {
                name: {
                    required: true,
                    minlength: 4,
                },
                phone: {
                    required: true,
                    maxlength: 13,
                    minlength: 9,
                },
                region: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: `"Ism va Familiya" maydoni bo'sh bo'lmasligi kerak.`,
                    minlength: `"Ism va Familiya" майдонида камида 4 та белги бўлиши керак.`,
                },
                phone: {
                    required: `"Telefon raqam" maydoni bo'sh bo'lmasligi керак.`,
                    maxlength: `Telefon raqamingizni to'liq kiriting`,
                    minlength: `Telefon raqamingizni to'liq kiriting`,
                },
                region: {
                    required: `'Ҳудудни танланг' bo‘sh bo'lmasligi керак.`,
                },
            },
        });
    });
    </script>
</body>

</html>

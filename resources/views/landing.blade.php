<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=480">
    <title><?php echo htmlspecialchars($landing->title)?></title>
    <base href="{{ url('http://127.0.0.1:8000/l/') }}">
    <link rel="stylesheet" href="{{ asset('l/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('l/Montserrat.css') }}">
    <link rel="stylesheet" href="{{ asset('l/styles.css') }}">

    <style>
        /* Your custom styles here */
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
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            width: auto;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Input field styling */
        .field {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        /* Form group styling */
        .form-group {
            position: relative;
            margin-bottom: 20px;
        }
    </style>

    <!-- Meta Pixel Code -->
    @if (!empty($stream->pixel_id))
        <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod ?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq) f._fbq=n;
                n.push=n;
                n.loaded=!0;n.version='2.0';
                n.queue=[];
                t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)
            }(window,document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?php echo htmlspecialchars($stream->pixel_id)?>');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                 src="https://www.facebook.com/tr?id=<?php echo htmlspecialchars($stream->pixel_id)?>&ev=PageView&noscript=1" />
        </noscript>
    @endif
    <!-- End Meta Pixel Code -->
</head>

<body>
<div class="main_wrapper">
    <header class="offer_section offer3">
        <h1 class="main_title"><?php echo htmlspecialchars($landing->title)?></h1>
        <div class="info_block">
            <p class="subtitle"><?php echo htmlspecialchars($landing->subtitle) ?></p>
            <img src="../storage/<?php echo htmlspecialchars($landing->img1) ?>" alt="Product Image">
        </div>
        <div class="price_block">
            <div class="price_item old">
                <div class="text">Yetkazib berish hududga qarab 25.000 dan boshlanadi</div>
            </div>
            <div class="price_item new">
                <div class="text"></div>
                <div class="value">
                        <span class="price_only7126">
                            {{ number_format($product->salePrice ?? 0, 0, '.', ' ') }} so'm
                        </span>
                </div>
            </div>
        </div>
        <div class="benefits_block clearfix">
            <div class="benefit_item">
                <img src="../storage/<?php echo htmlspecialchars($landing->img2) ?>" alt="Benefit 1">
            </div>
            <div class="benefit_item">
                <img src="../storage/<?php echo htmlspecialchars($landing->img3) ?>" alt="Benefit 2">
            </div>
            <div class="benefit_item">
                <img src="../storage/<?php echo htmlspecialchars($landing->img4) ?>" alt="Benefit 3">
            </div>
        </div>
    </header>
    <section class="use_section">
        <div style="text-align: center; padding: 7px 15px 15px 15px;">
            <p style="font-weight: 500; font-size: 18px;">
                <?php echo htmlspecialchars($landing->description) ?>
            </p>
        </div>
        <ul class="list2 marker1">
            <li><?php echo htmlspecialchars($landing->text1) ?></li>
            <li><?php echo htmlspecialchars($landing->text2) ?></li>
            @if (!empty($landing->text3))
                <li><?php echo htmlspecialchars($landing->text3) ?></li>
            @endif
            <li><b>Oldindan hech qanday to'lov yo'q ✅</b></li>
            <li><b>Yetkazib berish hududga qarab 25.000 dan boshlanadi 🚚</b></li>
        </ul>
    </section>
    <section class="offer_section offer3 order">
        @if (session('success'))
            <div class="alert alert-success" style="color: green; font-weight: bold;">
                {{ session('success') }}
            </div>
        @endif
        <form class="main-order-form order_form" id="order1" action="{{ route('landing.send') }}" method="post">
            @csrf
            <input type="hidden" name="source" value="<?php echo htmlspecialchars($stream->source ?? ''); ?>">
            <input type="hidden" name="store" value="<?php echo htmlspecialchars($landing->store); ?>">
            <input type="hidden" name="article" value="<?php echo htmlspecialchars($landing->article); ?>">
            <input type="hidden" name="pixel_id" value="<?php echo htmlspecialchars($stream->pixel_id ?? ''); ?>">
            <input type="hidden" name="link" value="<?php echo htmlspecialchars($stream->link ?? ''); ?>">
            <input type="hidden" name="two_plus_one" value="<?php echo htmlspecialchars($product->two_plus_one ?? ''); ?>">
            <input type="hidden" name="free2" value="<?php echo htmlspecialchars($product['free2']); ?>">
            <input type="hidden" name="free1" value="<?php echo htmlspecialchars($product['free1']); ?>">
            <input type="hidden" name="pvz" value="<?php echo htmlspecialchars($product['pvz']); ?>">

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
                <label for="name" class="error-message">
                </label>
                <input class="field" type="text" id="name" name="name" placeholder="Ism va Familiya" value="">
            </div>
            <div class="form-group">
                <label for="phone" class="error-message">
                </label>
                <input class="field" type="tel" id="id_phone_number" name="phone" placeholder="Telefon raqam" value="">
            </div>
            <button class="button">Buyurtma berish</button>
        </form>
        <br>
    </section>
    <footer class="footer_section">
        <div style="margin:15px 0; text-align:center;">
            <span style="font-size: 22px; font-weight: bold;">Tezkor aloqa</span><br>
            <span style="font-size: 18px; font-weight: bold;">Telefon raqam: <a style="font-size: 15px;" href="tel:+998781139994">78 113 99-94</a></span><br>
            <span style="font-size: 18px; font-weight: bold;">Telegram: <a style="font-size: 15px;" href="https://t.me/mgoodsuz">@Mgoodsuz</a></span><br>
        </div>
    </footer>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


<script src="{{asset('l/jquery.min.js')}}"></script>
<script src="{{asset('l/scripts.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        // Apply input mask to the phone number field
        $('#id_phone_number').inputmask("+\\9\\98 (99) 999-99-99");

        // Clear error messages on input focus
        $("input, select").on("focus", function () {
            $(this).prev("label.error-message").html("");
        });

        // Add custom validator method for phone number completeness
        $.validator.addMethod("phoneComplete", function (value, element) {
            return $(element).inputmask("isComplete");
        }, "Telefon raqamingizni to'liq kiriting");

        // Initialize form validation
        $("#order1").validate({
            errorPlacement: function (error, element) {
                element.prev("label.error-message").html(error);
            },
            rules: {
                name: {
                    required: true,
                    minlength: 4,
                },
                phone: {
                    required: true,
                    phoneComplete: true,
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
                    phoneComplete: `Telefon raqamingizni to'liq kiriting`,
                },
                region: {
                    required: `'Ҳудудни танланг' bo‘sh bo'lmasligi kerak.`,
                },
            },
            submitHandler: function (form) {
                // Optionally, process the phone number before submission
                var phoneInput = $('#id_phone_number');
                var rawPhoneNumber = phoneInput.inputmask('unmaskedvalue'); // Get the unmasked value
                phoneInput.val(rawPhoneNumber); // Set the unmasked value back to the input

                form.submit();
            }
        });
    });
</script>
</body>

</html>

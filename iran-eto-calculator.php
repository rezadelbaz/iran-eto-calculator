<?php
/*
Plugin Name: Iran ETo Calculator
Plugin URI: https://watereng.ir/help
Description: A plugin to calculate and display ETo forecast for Iranian cities.
Version: 1.2.3
Author: Reza Delbaz
Author URI: https://watereng.ir
*/

class Iran_ETo_Calculator
{
    private $provinces;
    private $city_names_fa;

    public function __construct()
    {
        $this->provinces = [
            'آذربایجان شرقی' => [
                'Tabriz' => '38.0962,46.2738',
                'Maragheh' => '37.3919,46.2391',
                'Marand' => '38.4329,45.7749',
                'Ahar' => '38.4774,47.0699',
            ],
            'آذربایجان غربی' => [
                'Urmia' => '37.5550,45.0725',
                'Khoy' => '38.5503,44.9521',
                'Mahabad' => '36.7631,45.7210',
                'Bukan' => '36.5210,46.2089',
            ],
            'اردبیل' => [
                'Ardabil' => '38.2498,48.2933',
                'Parsabad' => '39.6482,47.9174',
                'Meshginshahr' => '38.3989,47.6810',
                'Khalkhal' => '37.6189,48.5258',
            ],
            'اصفهان' => [
                'Isfahan' => '32.6546,51.6680',
                'Kashan' => '33.9850,51.4096',
                'Najafabad' => '32.6344,51.3670',
                'Shahinshahr' => '32.8579,51.5529',
                'Natanz' => '33.5110,51.9160',
            ],
            'البرز' => [
                'Karaj' => '35.8400,50.9391',
                'Nazarabad' => '35.9545,50.6061',
                'Savojbolagh' => '36.0333,50.8333',
                'Hashtgerd' => '35.9619,50.6800',
            ],
            'ایلام' => [
                'Ilam' => '33.6374,46.4226',
                'Dehloran' => '32.6941,47.2679',
                'Abdanan' => '32.9926,47.4198',
                'Darrehshahr' => '33.1396,47.3760',
            ],
            'بوشهر' => [
                'Bushehr' => '28.9234,50.8203',
                'BandarGanaveh' => '29.5791,50.5170',
                'Borazjan' => '29.2699,51.2189',
                'Kangan' => '27.8376,52.0623',
            ],
            'تهران' => [
                'Tehran' => '35.6892,51.3890',
                'Rey' => '35.5917,51.4394',
                'Shemiranat' => '35.9344,51.5950',
                'Eslamshahr' => '35.5522,51.2350',
            ],
            'چهارمحال و بختیاری' => [
                'Shahrekord' => '32.3256,50.8644',
                'Borujen' => '31.9652,51.2873',
                'Lordegan' => '31.5103,50.8294',
                'Farsan' => '32.2569,50.5640',
            ],
            'خراسان جنوبی' => [
                'Birjand' => '32.8649,59.2211',
                'Qayen' => '33.7265,59.1844',
                'Ferdows' => '34.0186,58.1720',

            ],
            'خراسان رضوی' => [
                'Mashhad' => '36.2970,59.6062',
                'Neyshabur' => '36.2140,58.7958',
                'Sabzevar' => '36.2126,57.6819',
                'TorbatHeydarieh' => '35.2740,59.2195',
            ],
            'خراسان شمالی' => [
                'Bojnurd' => '37.4747,57.3290',
                'Shirvan' => '37.3967,57.9295',
                'Esfarayen' => '37.0765,57.5100',
                'ManehSamanqan' => '37.8500,56.9167',
            ],
            'خوزستان' => [
                'Ahvaz' => '31.3183,48.6706',
                'Abadan' => '30.3392,48.3043',
                'Dezful' => '32.3814,48.4059',
                'Khorramshahr' => '30.4408,48.1664',
            ],
            'زنجان' => [
                'Zanjan' => '36.6764,48.4963',
                'Abhar' => '36.1468,49.2180',
                'Khorramdarreh' => '36.2130,49.1915',
                'Tarom' => '36.9500,48.9000',
            ],
            'سمنان' => [
                'Semnan' => '35.5769,53.3950',
                'Damghan' => '36.1683,54.3429',
                'Shahrud' => '36.4182,54.9763',
                'Garmsar' => '35.2182,52.3409',
            ],
            'سیستان و بلوچستان' => [
                'Zahedan' => '29.4963,60.8629',
                'Zabol' => '31.0287,61.5012',
                'Iranshahr' => '27.2024,60.6848',
                'Chabahar' => '25.2919,60.6430',
            ],
            'فارس' => [
                'Shiraz' => '29.5918,52.5837',
                'Marvdasht' => '29.8742,52.8020',
                'Jahrom' => '28.5000,53.5605',
                'Fasa' => '28.9383,53.6482',
            ],
            'قزوین' => [
                'Qazvin' => '36.2797,50.0049',
                'Takestan' => '36.0696,49.6959',
                'Abyek' => '36.0667,50.5500',
                'BuinZahra' => '35.7667,50.0578',
            ],
            'قم' => [
                'Qom' => '34.6416,50.8746',
                'Jafarieh' => '34.7833,50.5000',
                'Kahak' => '34.3833,50.8667',
                'Qanavat' => '34.6167,51.0333',
            ],
            'کردستان' => [
                'Sanandaj' => '35.3140,46.9923',
                'Saqqez' => '36.2499,46.2735',
                'Marivan' => '35.5183,46.1830',
                'Baneh' => '35.9975,45.8853',
            ],
            'کرمان' => [
                'Kerman' => '30.2832,57.0788',
                'Sirjan' => '29.4514,55.6802',
                'Rafsanjan' => '30.4067,55.9939',
                'Bam' => '29.1060,58.3570',
            ],
            'کرمانشاه' => [
                'Kermanshah' => '34.3142,47.0650',
                'EslamabadGharb' => '34.1094,46.5275',
                'Harsin' => '34.2721,47.5861',
                'Kangavar' => '34.5044,47.9653',
            ],
            'کهگیلویه و بویراحمد' => [
                'Yasuj' => '30.6682,51.5880',
                'Dehdasht' => '30.7937,50.5646',
                'Gachsaran' => '30.3586,50.7981',
                'Likak' => '30.9000,50.4167',
            ],
            'گلستان' => [
                'Gorgan' => '36.8427,54.4329',
                'GonbadKavus' => '37.2500,55.1672',
                'Aliabad' => '36.9082,54.8930',
                'BandarTorkaman' => '36.9010,54.0707',
            ],
            'گیلان' => [
                'Rasht' => '37.2808,49.5832',
                'BandarAnzali' => '37.4727,49.4622',
                'Lahijan' => '37.2073,50.0039',
                'Rudsar' => '37.1376,50.2880',
            ],
            'لرستان' => [
                'Khorramabad' => '33.4878,48.3558',
                'Borujerd' => '33.8973,48.7516',
                'Dorud' => '33.4955,49.0578',
                'Aligudarz' => '33.4006,49.6949',
            ],
            'مازندران' => [
                'Sari' => '36.5633,53.0601',
                'Amol' => '36.4696,52.3507',
                'Babol' => '36.5513,52.6789',
                'Qaemshahr' => '36.4631,52.8600',
            ],
            'مرکزی' => [
                'Arak' => '34.0917,49.6892',
                'Saveh' => '35.0213,50.3566',
                'Khomeyn' => '33.6423,50.0789',
                'Mahallat' => '33.9110,50.4532',
            ],
            'هرمزگان' => [
                'BandarAbbas' => '27.1865,56.2808',
                'Minab' => '27.1467,57.0801',
                'Qeshm' => '26.9492,56.2716',
                'BandarLengeh' => '26.5579,54.8807',
            ],
            'همدان' => [
                'Hamedan' => '34.7992,48.5146',
                'Malayer' => '34.3016,48.8219',
                'Nahavand' => '34.1885,48.3769',
                'Tuyserkan' => '34.5480,48.4469',
            ],
            'یزد' => [
                'Yazd' => '31.8972,54.3678',
                'Meybod' => '32.2501,54.0166',
                'Ardakan' => '32.3100,54.0175',
                'Bafq' => '31.6035,55.4025',
            ],
        ];

        $this->city_names_fa = [
            'Tabriz' => 'تبریز',
            'Maragheh' => 'مراغه',
            'Marand' => 'مرند',
            'Ahar' => 'اهر',
            'Urmia' => 'ارومیه',
            'Khoy' => 'خوی',
            'Mahabad' => 'مهاباد',
            'Bukan' => 'بوکان',
            'Ardabil' => 'اردبیل',
            'Parsabad' => 'پارس‌آباد',
            'Meshginshahr' => 'مشگین‌شهر',
            'Khalkhal' => 'خلخال',
            'Isfahan' => 'اصفهان',
            'Kashan' => 'کاشان',
            'Najafabad' => 'نجف‌آباد',
            'Shahinshahr' => 'شاهین‌شهر',
            'Karaj' => 'کرج',
            'Nazarabad' => 'نظرآباد',
            'Savojbolagh' => 'ساوجبلاغ',
            'Hashtgerd' => 'هشتگرد',
            'Ilam' => 'ایلام',
            'Dehloran' => 'دهلران',
            'Abdanan' => 'آبدانان',
            'Darrehshahr' => 'دره‌شهر',
            'Bushehr' => 'بوشهر',
            'BandarGanaveh' => 'بندر گناوه',
            'Borazjan' => 'برازجان',
            'Kangan' => 'کنگان',
            'Tehran' => 'تهران',
            'Rey' => 'ری',
            'Shemiranat' => 'شمیرانات',
            'Eslamshahr' => 'اسلامشهر',
            'Shahrekord' => 'شهرکرد',
            'Borujen' => 'بروجن',
            'Lordegan' => 'لردگان',
            'Farsan' => 'فارسان',
            'Birjand' => 'بیرجند',
            'Qayen' => 'قاین',
            'Ferdows' => 'فردوس',
            'Natanz' => 'نطنز',
            'Mashhad' => 'مشهد',
            'Neyshabur' => 'نیشابور',
            'Sabzevar' => 'سبزوار',
            'TorbatHeydarieh' => 'تربت حیدریه',
            'Bojnurd' => 'بجنورد',
            'Shirvan' => 'شیروان',
            'Esfarayen' => 'اسفراین',
            'ManehSamanqan' => 'مانه و سملقان',
            'Ahvaz' => 'اهواز',
            'Abadan' => 'آبادان',
            'Dezful' => 'دزفول',
            'Khorramshahr' => 'خرمشهر',
            'Zanjan' => 'زنجان',
            'Abhar' => 'ابهر',
            'Khorramdarreh' => 'خرمدره',
            'Tarom' => 'طارم',
            'Semnan' => 'سمنان',
            'Damghan' => 'دامغان',
            'Shahrud' => 'شاهرود',
            'Garmsar' => 'گرمسار',
            'Zahedan' => 'زاهدان',
            'Zabol' => 'زابل',
            'Iranshahr' => 'ایرانشهر',
            'Chabahar' => 'چابهار',
            'Shiraz' => 'شیراز',
            'Marvdasht' => 'مرودشت',
            'Jahrom' => 'جهرم',
            'Fasa' => 'فسا',
            'Qazvin' => 'قزوین',
            'Takestan' => 'تاکستان',
            'Abyek' => 'آبیک',
            'BuinZahra' => 'بوئین‌زهرا',
            'Qom' => 'قم',
            'Jafarieh' => 'جعفریه',
            'Kahak' => 'کهک',
            'Qanavat' => 'قنوات',
            'Sanandaj' => 'سنندج',
            'Saqqez' => 'سقز',
            'Marivan' => 'مریوان',
            'Baneh' => 'بانه',
            'Kerman' => 'کرمان',
            'Sirjan' => 'سیرجان',
            'Rafsanjan' => 'رفسنجان',
            'Bam' => 'بم',
            'Kermanshah' => 'کرمانشاه',
            'EslamabadGharb' => 'اسلام‌آباد غرب',
            'Harsin' => 'هرسین',
            'Kangavar' => 'کنگاور',
            'Yasuj' => 'یاسوج',
            'Dehdasht' => 'دهدشت',
            'Gachsaran' => 'گچساران',
            'Likak' => 'لیکک',
            'Gorgan' => 'گرگان',
            'GonbadKavus' => 'گنبد کاووس',
            'Aliabad' => 'علی‌آباد',
            'BandarTorkaman' => 'بندر ترکمن',
            'Rasht' => 'رشت',
            'BandarAnzali' => 'بندر انزلی',
            'Lahijan' => 'لاهیجان',
            'Rudsar' => 'رودسر',
            'Khorramabad' => 'خرم‌آباد',
            'Borujerd' => 'بروجرد',
            'Dorud' => 'دورود',
            'Aligudarz' => 'الیگودرز',
            'Sari' => 'ساری',
            'Amol' => 'آمل',
            'Babol' => 'بابل',
            'Qaemshahr' => 'قائم‌شهر',
            'Arak' => 'اراک',
            'Saveh' => 'ساوه',
            'Khomeyn' => 'خمین',
            'Mahallat' => 'محلات',
            'BandarAbbas' => 'بندرعباس',
            'Minab' => 'میناب',
            'Qeshm' => 'قشم',
            'BandarLengeh' => 'بندر لنگه',
            'Hamedan' => 'همدان',
            'Malayer' => 'ملایر',
            'Nahavand' => 'نهاوند',
            'Tuyserkan' => 'تویسرکان',
            'Yazd' => 'یزد',
            'Meybod' => 'میبد',
            'Ardakan' => 'اردکان',
            'Bafq' => 'بافق',
        ];

        add_action('init', [$this, 'register_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    public function register_shortcode()
    {
        add_shortcode('iran_eto_calculator', [$this, 'shortcode_callback']);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('iran-eto-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', [], '1.2.3');
        wp_enqueue_script('chart-js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js', [], '3.7.0', true);
        wp_enqueue_script('iran-eto-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', ['jquery', 'chart-js'], '1.2.3', true);
        wp_enqueue_script('toastify-js', 'https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.js', [], '1.12.0', true);
        wp_enqueue_style('toastify-css', 'https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css', [], '1.12.0');
        wp_enqueue_style('anjoman-font', plugin_dir_url(__FILE__) . 'assets/fonts/anjoman-font.css', [], '1.0');

        wp_localize_script('iran-eto-script', 'iran_eto_params', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'rest_url' => rest_url('iran-eto/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
            'provinces' => $this->provinces,
            'city_names_fa' => $this->city_names_fa
        ]);
    }

    public function add_admin_menu()
    {
        add_menu_page(
            'تبخیر و تعرق',
            'تبخیر و تعرق',
            'manage_options',
            'iran-eto-settings',
            [$this, 'settings_page'],
            'dashicons-cloud',
            81
        );
    }

    public function register_settings()
    {
        register_setting('iran-eto-settings-group', 'iran_eto_api_key', 'sanitize_text_field');
    }

    public function settings_page()
    {
?>
        <div class="wrap">
            <h1>تنظیمات تبخیر و تعرق</h1>
            <p>برای استفاده از پلاگین، باید یک کلید API از <a href="https://www.visualcrossing.com/weather-api" target="_blank">VisualCrossing</a> دریافت کنید و در زیر وارد کنید.</p>
            <form method="post" action="options.php">
                <?php
                settings_fields('iran-eto-settings-group');
                $api_key = get_option('iran_eto_api_key', '');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="iran_eto_api_key">کلید API</label></th>
                        <td>
                            <input type="text" name="iran_eto_api_key" id="iran_eto_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" />
                            <p class="description">کلید API خود را از VisualCrossing وارد کنید.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
    <?php
    }

    public function shortcode_callback($atts)
    {
        $api_key = get_option('iran_eto_api_key');
        if (empty($api_key)) {
            return '<div class="iran-eto-error" style="text-align: center; color: red; padding: 20px;">' .
                'لطفاً کلید API خود را وارد کنید. <a href="' . admin_url('admin.php?page=iran-eto-settings') . '">وارد کردن کلید API</a>' .
                '</div>';
        }

        $atts = shortcode_atts(['province' => 'تهران', 'city' => 'Tehran'], $atts, 'iran_eto_calculator');
        $province = sanitize_text_field($atts['province']);
        $city = sanitize_text_field($atts['city']);

        if (!isset($this->provinces[$province][$city])) {
            $province = 'تهران';
            $city = 'Tehran';
        }

        $city_fa = isset($this->city_names_fa[$city]) ? $this->city_names_fa[$city] : $city;

        ob_start();
    ?>
        <div class="iran-eto-calculator-container" data-province="<?php echo esc_attr($province); ?>" data-city="<?php echo esc_attr($city); ?>" data-fa-name="<?php echo esc_attr($city_fa); ?>">
            <div class="iran-eto-selectors">
                <div class="iran-eto-province-selector">
                    <label for="iran-eto-province">انتخاب استان:</label>
                    <select id="iran-eto-province">
                        <?php foreach ($this->provinces as $prov_name => $cities) : ?>
                            <option value="<?php echo esc_attr($prov_name); ?>" <?php selected($province, $prov_name); ?>>
                                <?php echo esc_html($prov_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="iran-eto-city-selector">
                    <label for="iran-eto-city">انتخاب شهرستان:</label>
                    <select id="iran-eto-city">
                        <?php foreach ($this->provinces[$province] as $city_name => $coords) : ?>
                            <option value="<?php echo esc_attr($city_name); ?>" <?php selected($city, $city_name); ?>>
                                <?php echo esc_html($this->city_names_fa[$city_name] ?? $city_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button id="iran-eto-load-data" class="iran-eto-load-btn">نمایش اطلاعات</button>
            </div>
            <div class="iran-eto-loading" style="display: none;">در حال بارگذاری...</div>
            <div class="iran-eto-chart-container">
                <canvas id="iran-eto-chart"></canvas>
            </div>
            <div class="iran-eto-data-table">
                <table>
                    <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>تبخیر و تعرق (mm)</th>
                            <th>دما (°C)</th>
                            <th>رطوبت (%)</th>
                            <th>سرعت باد (m/s)</th>
                            <th>تابش خورشید (MJ/m²/day)</th>
                        </tr>
                    </thead>
                    <tbody id="iran-eto-data-body"></tbody>
                </table>
            </div>
            <button id="iran-eto-export-excel" class="iran-eto-export-btn">خروجی اکسل</button>
            <div>
                <p>توسعه: <a href="https://watereng.ir" target="_blank">مرجع مهندسی آب</a></p>
            </div>
        </div>
<?php
        return ob_get_clean();
    }

    public function register_rest_routes()
    {
        register_rest_route('iran-eto/v1', '/forecast/(?P<city>[a-zA-Z0-9-]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_eto_forecast'],
            'permission_callback' => '__return_true'
        ]);
    }

    public function get_eto_forecast($request)
    {
        $api_key = get_option('iran_eto_api_key');
        if (empty($api_key)) {
            return new WP_Error('no_api_key', 'کلید API وارد نشده است. لطفاً به تنظیمات پلاگین بروید و کلید API خود را وارد کنید.', ['status' => 403]);
        }

        $city = sanitize_text_field($request['city']);
        $province = null;

        foreach ($this->provinces as $prov_name => $cities) {
            if (isset($cities[$city])) {
                $province = $prov_name;
                break;
            }
        }

        if (!$province || !isset($this->provinces[$province][$city])) {
            return new WP_Error('invalid_city', 'شهر نامعتبر است.', ['status' => 404]);
        }

        $weather_data = $this->get_weather_data($city);
        if (!$weather_data) {
            return new WP_Error('no_data', 'داده‌ای برای این شهر یافت نشد.', ['status' => 404]);
        }

        $forecast = [];
        if (isset($weather_data['days']) && is_array($weather_data['days'])) {
            foreach ($weather_data['days'] as $day) {
                $date = $day['datetime'];
                $persian_date = $this->gregorian_to_jalali($date);
                $eto = $this->calculate_eto($day);
                $forecast[] = [
                    'date' => $date,
                    'persian_date' => $persian_date,
                    'eto' => round($eto, 2),
                    'temp_mean' => $day['temp'],
                    'humidity' => $day['humidity'],
                    'wind_speed' => $day['windspeed'] / 3.6, // تبدیل km/h به m/s
                    'solar_radiation' => $day['solarradiation'] * 0.0864 // تبدیل W/m² به MJ/m²/day
                ];
            }
        } else {
            return new WP_Error('invalid_data', 'داده‌های هواشناسی نامعتبر است.', ['status' => 500]);
        }

        return rest_ensure_response($forecast);
    }

    private function get_weather_data($city)
    {
        $api_key = get_option('iran_eto_api_key');
        if (empty($api_key)) {
            error_log("No API key provided for VisualCrossing.");
            return false;
        }

        $cache_key = 'iran_eto_weather_' . md5($city);
        $cached_data = get_transient($cache_key);

        if ($cached_data !== false) {
            error_log("Returning cached weather data for $city");
            return $cached_data;
        }

        $coordinates = null;
        foreach ($this->provinces as $province => $cities) {
            if (isset($cities[$city])) {
                $coordinates = $cities[$city];
                break;
            }
        }

        if (!$coordinates) {
            error_log("No coordinates found for city: $city");
            return false;
        }

        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime('+14 days'));

        $endpoint = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/{$coordinates}/{$start_date}/{$end_date}";
        $params = [
            'unitGroup' => 'metric',
            'include' => 'days',
            'key' => $api_key,
            'contentType' => 'json'
        ];

        $url = add_query_arg($params, $endpoint);
        error_log("Generated URL for $city: $url");

        $response = wp_remote_get($url, ['timeout' => 30, 'sslverify' => false]);

        if (is_wp_error($response)) {
            error_log("Error fetching weather data for $coordinates: " . $response->get_error_message());
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data['days'])) {
            error_log("No 'days' data returned for $coordinates: $body");
            return false;
        }

        set_transient($cache_key, $data, 24 * HOUR_IN_SECONDS);
        return $data;
    }

    private function gregorian_to_jalali($date)
    {
        $date = new DateTime($date);
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');

        $g_days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

        $gy = $year - 1600;
        $gm = $month - 1;
        $gd = $day - 1;

        $g_day_no = 365 * $gy + floor(($gy + 3) / 4) - floor(($gy + 99) / 100) + floor(($gy + 399) / 400);
        for ($i = 0; $i < $gm; ++$i) {
            $g_day_no += $g_days_in_month[$i];
        }
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0))) {
            $g_day_no++;
        }
        $g_day_no += $gd;

        $j_day_no = $g_day_no - 79;
        $j_np = floor($j_day_no / 12053);
        $j_day_no %= 12053;

        $jy = 979 + 33 * $j_np + 4 * floor($j_day_no / 1461);
        $j_day_no %= 1461;

        if ($j_day_no >= 366) {
            $jy += floor(($j_day_no - 1) / 365);
            $j_day_no = ($j_day_no - 1) % 365;
        }

        $jm = 0;
        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) {
            $j_day_no -= $j_days_in_month[$i];
            $jm++;
        }
        $jd = $j_day_no + 1;

        $persian_months = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
        return sprintf("%d %s %d", $jd, $persian_months[$jm], $jy);
    }

    private function calculate_eto($day)
    {
        $temp = $day['temp'];
        $humidity = $day['humidity'];
        $wind_speed = $day['windspeed'] / 3.6; // km/h به m/s
        $solar_radiation = $day['solarradiation'] * 0.0864; // W/m² به MJ/m²/day

        $delta = (4098 * (0.6108 * exp((17.27 * $temp) / ($temp + 237.3)))) / pow($temp + 237.3, 2);
        $psy = 0.000665 * 101.3 * pow((293 - 0.0065 * 0) / 293, 5.26);
        $et0 = (0.408 * $delta * ($solar_radiation - 0) + $psy * (900 / ($temp + 273)) * $wind_speed * (0.6108 * exp((17.27 * $temp) / ($temp + 237.3)) * (1 - $humidity / 100))) / ($delta + $psy * (1 + 0.34 * $wind_speed));

        return max($et0, 0);
    }
}

new Iran_ETo_Calculator();

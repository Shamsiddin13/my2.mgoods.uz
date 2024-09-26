<?php

namespace App\Models;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;

class RetailCRM extends Model
{
    /**
     * ссылка на аккаунт RetailCRM
     */
    const API_URL = 'https://mgoods.retailcrm.ru';

    /**
     * API-ключ
     * Необходимо для каждого магазина использовать свой ключ
     */
    const API_KEY = '2SiK5EFNAjOPsx3NPvte0z7bZGQVoULe';

    /**
     * Поле "внешний код" торгового предложения
     */
    protected $item_external_code = '';

    /**
     * Символьный код магазина в RetailCRM
     */
    protected $site = '';

    public function __construct($site, $item_external_code = null)
    {
        $this->site = $site;
        $this->item_external_code = $item_external_code;
    }

    public function isDuplicate($order)
    {
        if (empty($order['phone'])) {
            return false;
        }
        $now = new Datetime('now', new DateTimeZone('Asia/Tashkent'));
        $date = new Datetime('36 hours ago', new DateTimeZone('Asia/Tashkent'));

        $createdAtFrom = $date->format('Y-m-d H:i:s');


        $filter = [
            'site' => $order['site'],
            'filter[customer]' => $order['phone'],
            'limit' => 100,
        ];

        $response = self::request('orders', 'get', $filter);


        if ($response && !empty($response['orders'])) {
            foreach ($response['orders'] as $o) {
                $phone = false;
                $statuses = false;
                $date_correct = false;
                $items = false;

                if (empty($o['phone'])) {
                    continue;
                }

                $order_date = new Datetime($o['createdAt']);

                $interval = $now->diff($order_date);
                $total_days = $interval->days;
                $hours = $interval->h;

                $minutes = $interval->i;
                $seconds = $interval->s;

                if ($hours > 36) {
                    $date_correct = true;
                }

                if (!empty($o['items']['0']['offer']['externalId']) && $o['items']['0']['offer']['externalId'] === $this->item_external_code) {
                    $items = true;
                }

                if ($o['phone'] === $order['phone'] || str_replace(
                        ['(', ')', '-', '+', ' '],
                        '',
                        $o['phone']
                    ) === str_replace(['(', ')', '-', '+', ' '], '', $order['phone'])) {
                    $phone = true;
                }

                if (in_array($o['status'], ['new', 'no-call', 'delivery-late', 'new-1'])) {
                    $statuses = true;
                }

                if (!$date_correct && $statuses && $phone && $items) {
                    return true;
                }
            }
        }

        return false;
    }



    public function sendToCrm($order)
    {
        if (empty($order['phone'])) {
            return false;
        }

        return self::request('orders/create', 'post', ['site' => $this->site, 'order' => json_encode($order)]);
    }

    public function getOrderFromPost()
    {
        $data = [
            'name' => isset($_POST['name']) ? $_POST['name'] : '',
            'utm_source' => isset($_POST['source']) ? $_POST['source'] : '',
            'utm_campaign' => isset($_POST['utm_campaign']) ? $_POST['utm_campaign'] : '',
            'utm_medium' => isset($_POST['utm_medium']) ? $_POST['utm_medium'] : '',
            'utm_keyword' => isset($_POST['utm_keyword']) ? $_POST['utm_keyword'] : '',
            'param_source' => isset($_POST['source']) ? $_POST['source'] : '',
            'param_placement' => isset($_POST['placement']) ? $_POST['placement'] : '',
            'phone' => isset($_POST['phone']) ? $_POST['phone'] : '',
            'region' => isset($_POST['region']) ? $_POST['region'] : '',
            'link' => isset($_POST['link']) ? $_POST['link'] : '',
            'two_plus_one' => isset($_POST['two_plus_one']) ? $_POST['two_plus_one'] : ''
        ];

        extract($data);

        if (!empty($_POST['Source'])) {
            $param_source = $_POST['Source'];
        }
        if (!empty($_POST['Source%20name'])) {
            $param_source = $_POST['Source%20name'];
        }
        if (!empty($_POST['Source+name'])) {
            $param_source = $_POST['Source+name'];
        }
        if (!empty($_POST['Source_name'])) {
            $param_source = $_POST['Source%20name'];
        }
        if (!empty($_POST['Placement'])) {
            $param_placement = $_POST['Placement'];
        }

        $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        $phone = str_replace(['+', '-'], '', $phone);

        $order = [
            'site' => $this->site,
            'phone' => $phone,
            'firstName' => $name,
            'delivery' => [
                'address' => [
                    'region' => $region
                ]
            ],
            'source' => [
                'source' => $utm_source,
                'medium' => $utm_medium,
                'campaign' => $utm_campaign,
                'keyword' => $utm_keyword
            ],
            'customFields' => []
        ];

        if (!empty($param_source)) {
            $order['customFields']['source'] = $param_source;
        }
        if (!empty($param_placement)) {
            $order['customFields']['placement'] = $param_placement;
        }
        if (!empty($link)) {
            $order['customFields']['link'] = $link;
        }
        if (!empty($two_plus_one)) {
            $order['customFields']['two_plus_one'] = $two_plus_one;
        }

        if (!empty($this->item_external_code)) {
            $order['items'] = [
                [
                    'offer' => [
                        'externalId' => $this->item_external_code,
                    ]
                ]
            ];
        }

        return $order;
    }

    protected function request($api_method, $http_method, array $parameters = [])
    {
        $url = self::API_URL . '/api/v5/' . trim($api_method, '/');

        $parameters = array_merge($parameters, ['apiKey' => self::API_KEY]);

        if ('get' === $http_method && count($parameters)) {
            $url .= '?' . http_build_query($parameters, '', '&');
        }

        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_URL, $url);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandler, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curlHandler, CURLOPT_FAILONERROR, false);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curlHandler, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandler, CURLOPT_CONNECTTIMEOUT, 30);

        if ('post' === strtolower($http_method)) {
            curl_setopt($curlHandler, CURLOPT_POST, true);
            curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $parameters);
        }

        $responseBody = json_decode(curl_exec($curlHandler), true);

        $statusCode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);

        return $responseBody;
    }

    protected function __clone()
    {
        // ...
    }

    public function __wakeup()
    {
        // ...
    }

}

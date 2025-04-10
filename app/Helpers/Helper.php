<?php

    function getDockerURL($path, $port) {
        return 'http://localhost:'.$port.'/'.$path;
    }

    function getDockerHeaders() {
        return ['Content-Type' => 'application/json'];
    }

    function getShopifyURLForStore($endpoint, $store,$version=null) {
    $api_version = $version??config('custom.shopify_api_version');
        return checkIfStoreIsPrivate($store) ? 
            'https://'.$store['api_key'].':'.$store['api_secret_key'].'@'.$store['myshopify_domain'].'/admin/api/'.config('custom.shopify_api_version').'/'.$endpoint 
            :
            'https://'.$store['myshopify_domain'].'/admin/api/'.$api_version.'/'.$endpoint;
    }

    function getShopifyHeadersForStore($store, $method = 'GET') {
        return $method == 'GET' ? [
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $store['access_token']
        ] : [
            'Accept' => 'application/json',
           'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $store['access_token']
        ];
    }

    function getGraphQLHeadersForStore($store) {
        return checkIfStoreIsPrivate($store) ? [
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $store['api_secret_key'],
            'X-GraphQL-Cost-Include-Fields' => true
        ] : [
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $store['access_token'],
            'X-GraphQL-Cost-Include-Fields' => true
        ];
    }

    function checkIfStoreIsPrivate($store) {
        return isset($store['api_key']) && isset($store['api_secret_key'])
                && $store['api_key'] !== null && $store['api_secret_key'] !== null  
                && strlen($store['api_key']) > 0 && strlen($store['api_secret_key']) > 0; 
    }

    // In a helper file (e.g., app/Helpers/helpers.php)
    if (! function_exists('getOrderStatusBadge')) {
        function getOrderStatusBadge($status)
        {
            switch ($status) {
                case 'processing' :
                    return '<span class="badge badge-inline badge-danger">'.$status.'</span>';
                case 'distributed' :
                    return '<span class="badge badge-inline badge-info">'.$status.'</span>';
                case 'prepared' :
                    return '<span class="badge badge-inline badge-success">'.$status.'</span>';
                case 'shipped' :
                    return '<span class="badge badge-inline badge-primary">'.$status.'</span>';
                case 'hold' :
                    return '<span class="badge badge-inline badge-warning">'.$status.'</span>';
                case 'reviewed' :
                    return '<span class="badge badge-inline badge-secondary">'.$status.'</span>';
                case 'cancelled' :
                    return '<span class="badge badge-inline badge-danger">'.$status.'</span>';
                case 'fulfilled' :
                    return '<span class="badge badge-inline badge-dark">'.$status.'</span>';
                case 'returned' || 'Returned' :
                    return '<span class="badge badge-inline badge-danger">'.$status.'</span>';
                case 'delivered' || 'Delivered' :
                    return '<span class="badge badge-inline badge-success">'.$status.'</span>';
                case 'partial_return' || 'Partial Return' :
                    return '<span class="badge badge-inline badge-warning">'.$status.'</span>';
                default:
                    return '<span class="badge badge-secondary">'.$status.'</span>';
            }
        }
    }


    function getCurrencySymbol($code) {
        switch($code) {
            case 'INR': return '₹ ';
            case 'EUR': return '€ ';
            case 'UAH': return '₴ ';
            case 'PLN': return 'zł ';
            case 'RON': return 'Lei ';
            case 'CZK': return 'Kč ';
            case 'SEK': return 'SEK ';
            case 'HUF': return 'Ft ';
            case 'BYN': return 'BYN ';
            case 'BGN': return 'лв. ';
            case 'DKK': return 'DKK ';
            case 'NOK': return 'NOK ';
            case 'HRK': return 'kn ';
            case 'MDL': return 'L ';
            case 'BAM': return 'KM ';
            case 'ALL': return 'Lek ';
            case 'MKD': return 'ден ';
            case 'ISK': return 'kr ';
            case 'SAR': return 'ر.س';
            case 'ARS':
            case 'CAD': return 'C$ ';
            case 'NZD': return 'NZ$ ';
            case 'CLP':
            case 'COP':
            case 'MXN':
            case 'SGD': return 'S$ ';
            case 'AUD': return 'A$ ';
            case 'USD': return '$ ';
            case 'GBP': return '£ ';
            case 'CHF': return 'CHF ';
            case 'ZAR': return 'S ';
            case 'RUB': return '₽ ';
            case 'QAR': return 'ر.ق ';
            case 'MUR':
            case 'NPR': return '₨ ';
            case 'MYR': return 'RM ';
            case 'KPW':
            case 'KRW': return '₩ ';
            case 'JPY': return '¥ ';
            case 'IDR': return 'Rp ';
            case 'VND': return '₫ ';
            case 'KWD': return 'د.ك ';
            case 'AED': return 'د.إ ';
            case 'OMR': return 'ر.ع. ';
            case 'BOB': return '$ ';
            case 'AZN': return '₼ ';
            case 'THB': return '฿ ';
            default : return $code;
        }
    }

    function determineIfAppIsEmbedded() {
        return config('custom.app_embedded') == 'true' || config('custom.app_embedded') == true;
    }


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="" />
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="<?php echo asset('vendor/font-awesome/css/font-awesome.min.css'); ?>" type="text/css">
    <!-- Drip icon font-->
    <link rel="stylesheet" href="<?php echo asset('vendor/dripicons/webfont.css'); ?>" type="text/css">

    <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.min.js'); ?>"></script>
    <style type="text/css">
        * {
            font-size: 16px;
            line-height: 24px;
            font-family: 'system-ui';
            text-transform: capitalize;
            color: rgb(0, 0, 0, 100) !important;
            padding: 0;
            margin: 0;
            font-weight: bold;
        }

        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor: pointer;
        }

        .inv-number-bc {
            width: 90%;
            height: 60px;
            margin: 0 auto;
            text-align: center;
            display: block;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .ask-for-exchange {
            width: 100%;
            height: 100%;
            position: fixed;
            content: "";
            top: 0;
            left: 0;
            background-color: #000000bf;
            text-align: center;

        }

        .ask-for-exchange h4 {
            font-size: 50px;
            margin-top: 180px;
            color: #fff !important;
            margin-bottom: 90px;
        }

        .yes-exchange,
        .no-exchange {
            color: #fff !important;
            padding: 15px 30px;
            border-radius: 12px;
            margin: 50px;
            text-decoration: none;
            font-size: 30px;
        }

        .yes-exchange {
            background-color: brown;
        }

        .no-exchange {
            background-color: #2a3da5;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }

        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }

        tr {
            border-bottom: 1px dotted #ddd;
        }

        td,
        th {
            padding: 5px 0;
            width: 50%;
        }

        table {
            width: 100%;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        .big-title {
            font-weight: bold;
        }

        .sub-address {
            padding: 0 !important;
            font-size: 14px;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        small {
            font-size: 11px;
        }

        .exchange-popup {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #00000082;
            padding-top: 250px;
            width: 100%;
            height: 100%;
            text-align: center;

        }

        .box-container-for-popup {
            width: 60%;
            margin: 0 auto;
            background-color: #ffffffdb;
            height: 200px;
            border-radius: 30px;
            padding-top: 100px;
        }

        .exchange-popup h1 {
            font-size: 30px;
            color: #971212 !important;
            line-height: 1.3;

        }

        .exchange-popup a {
            padding: 10px;
            display: inline-block;
            width: 27%;
            border-radius: 15px;
            text-decoration: none;
            margin: 15px;

        }

        @media  print {
            * {
                font-size: 14px;
                line-height: 20px;
            }

            td,
            th {
                padding: 5px 0;
            }

            .hidden-print {
                display: none !important;
            }

            .big-title {
                font-weight: bold;
            }

            .sub-address {
                text-align: left;
                padding: 0;
            }

            @page  {
                margin: 0;
            }

            body {
                margin: 0.5cm;
                margin-bottom: 1.6cm;
            }
        }
    </style>
</head>

<body>

    <div style="max-width:400px;margin:0 auto">
        <div class="hidden-print">
            <table>
                <tr>
                    <td><a href="s" class="btn btn-info"><i class="fa fa-arrow-left"></i>
                            <?php echo e(trans('file.Back')); ?></a> </td>
                    <!-- <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> <?php echo e(trans('file.Print')); ?></button></td> -->
                </tr>
            </table>
            <br>
        </div>
        <div class="centered">
            <img src="<?php echo e(asset('assets/img/logo.png')); ?>" width="60%">
            <h2>Online Order</h2>
        </div>
        <?php
            use Milon\Barcode\DNS1D;
            $barcode = new DNS1D();
            echo ' <p style="text-align: center;display: block";font-size:20px>' .
                $barcode->getBarcodeSVG('Lvs' . $data['order_id'], 'C128', 3, 37, 'Black', false) .
                '</p>';
        ?>

        <p> Order Placing Date: <?php echo e($order->created_at_date); ?><br>
            Shipping Date : <?php echo e(date('Y-m-d H:i:s')); ?> <br>
            Order Number : Lvs<?php echo e($data['order_id']); ?> <br>
            Customer Name : <?php echo e($order['shipping_address']['name']); ?><br>
            City: <?php echo e($order['shipping_address']['province']); ?><br>
            Zone: <?php echo e($order['shipping_address']['city']); ?><br>
            Phone: <?php echo e($order['shipping_address']['phone']); ?><br>
            Address: <?php echo e($order['shipping_address']['address1']); ?><br>
            Cashier: <?php echo e($auth_user); ?>

        </p>

        <table>

            <thead>
                <tr style="border-top: 2px solid #000 !important;border-bottom: 2px solid #000 !important;">
                    <th colspan="2" style="text-align:left;">Product</th>
                    <th colspan="2" style="text-align:right;">Price</th>
                </tr>
            </thead>

            <tbody>

                <?php $__currentLoopData = $prepare->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!in_array($prod->product_id, $refunds)): ?>
                        <tr>
                            <td colspan="2"> <?php echo e($prod->product_name); ?> X <?php echo e($prod['order_qty']); ?> <br></td>
                            <td colspan="2" style="text-align: right;">
                                <?php echo e($prod->price); ?> <br>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>

            <tfoot>

                <tr style="border-top: 2px solid #000 !important;border-bottom: 2px solid #000 !important;">
                    <th colspan="2">Shipping Fees</th>
                    <th style="text-align:right"><?php echo e($shipping_cost); ?></th>
                </tr>
                <tr style="border-top: 2px solid #000 !important;border-bottom: 2px solid #000 !important;">
                    <th colspan="2">SubTotal Order</th>
                    <th style="text-align:right"><?php echo e($order->subtotal_price); ?></th>
                </tr>
                <tr style="border-top: 2px solid #000 !important;border-bottom: 2px solid #000 !important;">
                    <th colspan="2">Total QTY</th>
                    <th style="text-align:right"><?php echo e($data['quantity']); ?></th>
                </tr>

                <tr style="border-top: 2px solid #000 !important;border-bottom: 2px solid #000 !important;">
                    <th colspan="2">Total Order</th>
                    <th style="text-align:right"><?php echo e($data['total']); ?></th>
                </tr>
                <tr style="border-top: 2px solid #000 !important;border-bottom: 2px solid #000 !important;">
                    <th colspan="2">Payment Method</th>
                    <th style="text-align:right">
                        <?php echo e(is_array($order->payment_gateway_names) && isset($order->payment_gateway_names[0]) ? $order->payment_gateway_names[0] : ''); ?>

                    </th>
                </tr>
                <tr style="border-top: 2px solid #000 !important;border-bottom: 2px solid #000 !important;">
                    <th colspan="2">Payment Status</th>
                    <th style="text-align:right"><?php echo e($order->getPaymentStatus()); ?></th>
                </tr>

            </tfoot>
        </table>
        <table>
            <tbody>



                <div class="centered"></div>

                <tr>
                    <td colspan="5" class="centered">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span style="font-size: 15px;display: inline-block;font-weight: bold;"> Our Branches</span>

                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="centered">
                        <div class="centered">
                            <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 72 72" style="width: 110px;background-color: #fff;">
                                <title>Branchs</title>
                                <g id="elements">
                                    <path
                                        d="M4.23,4.05H6.72V6.54H4.23Zm2.49,0H9.2V6.54H6.72Zm2.48,0h2.48V6.54H9.2Zm2.48,0h2.49V6.54H11.68Zm2.49,0h2.48V6.54H14.17Zm2.48,0h2.48V6.54H16.65Zm2.48,0h2.48V6.54H19.13Zm7.45,0h2.48V6.54H26.58ZM44,4.05h2.48V6.54H44Zm7.45,0h2.48V6.54H51.41Zm2.48,0h2.48V6.54H53.89Zm5,0h2.48V6.54H58.86Zm2.48,0h2.48V6.54H61.34Zm2.48,0H66.3V6.54H63.82Zm2.48,0h2.49V6.54H66.3Zm2.49,0h2.48V6.54H68.79Zm2.48,0h2.48V6.54H71.27Zm2.48,0h2.48V6.54H73.75ZM4.23,6.54H6.72V9H4.23Zm14.9,0h2.48V9H19.13Zm5,0h2.48V9H24.1Zm2.48,0h2.48V9H26.58Zm5,0H34V9H31.54Zm2.49,0h2.48V9H34Zm7.45,0H44V9H41.48Zm5,0h2.48V9H46.44Zm2.48,0h2.49V9H48.92Zm5,0h2.48V9H53.89Zm5,0h2.48V9H58.86Zm14.89,0h2.48V9H73.75ZM4.23,9H6.72V11.5H4.23Zm5,0h2.48V11.5H9.2Zm2.48,0h2.49V11.5H11.68Zm2.49,0h2.48V11.5H14.17Zm5,0h2.48V11.5H19.13Zm7.45,0h2.48V11.5H26.58Zm2.48,0h2.48V11.5H29.06Zm2.48,0H34V11.5H31.54ZM39,9h2.49V11.5H39Zm9.93,0h2.49V11.5H48.92Zm9.94,0h2.48V11.5H58.86Zm5,0H66.3V11.5H63.82ZM66.3,9h2.49V11.5H66.3Zm2.49,0h2.48V11.5H68.79Zm5,0h2.48V11.5H73.75ZM4.23,11.5H6.72V14H4.23Zm5,0h2.48V14H9.2Zm2.48,0h2.49V14H11.68Zm2.49,0h2.48V14H14.17Zm5,0h2.48V14H19.13Zm5,0h2.48V14H24.1Zm2.48,0h2.48V14H26.58Zm5,0H34V14H31.54Zm5,0H39V14H36.51Zm2.48,0h2.49V14H39Zm2.49,0H44V14H41.48Zm2.48,0h2.48V14H44Zm2.48,0h2.48V14H46.44Zm12.42,0h2.48V14H58.86Zm5,0H66.3V14H63.82Zm2.48,0h2.49V14H66.3Zm2.49,0h2.48V14H68.79Zm5,0h2.48V14H73.75ZM4.23,14H6.72v2.49H4.23Zm5,0h2.48v2.49H9.2Zm2.48,0h2.49v2.49H11.68Zm2.49,0h2.48v2.49H14.17Zm5,0h2.48v2.49H19.13Zm9.93,0h2.48v2.49H29.06Zm5,0h2.48v2.49H34Zm2.48,0H39v2.49H36.51ZM44,14h2.48v2.49H44Zm5,0h2.49v2.49H48.92Zm2.49,0h2.48v2.49H51.41Zm2.48,0h2.48v2.49H53.89Zm5,0h2.48v2.49H58.86Zm5,0H66.3v2.49H63.82Zm2.48,0h2.49v2.49H66.3Zm2.49,0h2.48v2.49H68.79Zm5,0h2.48v2.49H73.75ZM4.23,16.47H6.72V19H4.23Zm14.9,0h2.48V19H19.13Zm5,0h2.48V19H24.1Zm5,0h2.48V19H29.06Zm7.45,0H39V19H36.51Zm2.48,0h2.49V19H39Zm2.49,0H44V19H41.48Zm5,0h2.48V19H46.44Zm7.45,0h2.48V19H53.89Zm5,0h2.48V19H58.86Zm14.89,0h2.48V19H73.75ZM4.23,19H6.72v2.48H4.23Zm2.49,0H9.2v2.48H6.72ZM9.2,19h2.48v2.48H9.2Zm2.48,0h2.49v2.48H11.68Zm2.49,0h2.48v2.48H14.17Zm2.48,0h2.48v2.48H16.65Zm2.48,0h2.48v2.48H19.13Zm5,0h2.48v2.48H24.1Zm5,0h2.48v2.48H29.06Zm5,0h2.48v2.48H34Zm5,0h2.49v2.48H39Zm5,0h2.48v2.48H44Zm5,0h2.49v2.48H48.92Zm5,0h2.48v2.48H53.89Zm5,0h2.48v2.48H58.86Zm2.48,0h2.48v2.48H61.34Zm2.48,0H66.3v2.48H63.82Zm2.48,0h2.49v2.48H66.3Zm2.49,0h2.48v2.48H68.79Zm2.48,0h2.48v2.48H71.27Zm2.48,0h2.48v2.48H73.75ZM34,21.43h2.48v2.48H34Zm14.89,0h2.49v2.48H48.92Zm2.49,0h2.48v2.48H51.41ZM4.23,23.91H6.72V26.4H4.23Zm2.49,0H9.2V26.4H6.72Zm2.48,0h2.48V26.4H9.2Zm2.48,0h2.49V26.4H11.68Zm2.49,0h2.48V26.4H14.17Zm5,0h2.48V26.4H19.13Zm2.48,0H24.1V26.4H21.61Zm2.49,0h2.48V26.4H24.1Zm2.48,0h2.48V26.4H26.58Zm2.48,0h2.48V26.4H29.06Zm5,0h2.48V26.4H34Zm2.48,0H39V26.4H36.51Zm2.48,0h2.49V26.4H39Zm2.49,0H44V26.4H41.48Zm5,0h2.48V26.4H46.44Zm9.93,0h2.49V26.4H56.37Zm5,0h2.48V26.4H61.34Zm5,0h2.49V26.4H66.3Zm5,0h2.48V26.4H71.27Zm-67,2.49H6.72v2.48H4.23Zm12.42,0h2.48v2.48H16.65Zm5,0H24.1v2.48H21.61Zm5,0h2.48v2.48H26.58ZM44,26.4h2.48v2.48H44Zm7.45,0h2.48v2.48H51.41Zm2.48,0h2.48v2.48H53.89Zm2.48,0h2.49v2.48H56.37Zm2.49,0h2.48v2.48H58.86Zm2.48,0h2.48v2.48H61.34Zm2.48,0H66.3v2.48H63.82Zm9.93,0h2.48v2.48H73.75ZM9.2,28.88h2.48v2.48H9.2Zm2.48,0h2.49v2.48H11.68Zm2.49,0h2.48v2.48H14.17Zm5,0h2.48v2.48H19.13Zm5,0h2.48v2.48H24.1Zm2.48,0h2.48v2.48H26.58Zm5,0H34v2.48H31.54Zm2.49,0h2.48v2.48H34Zm2.48,0H39v2.48H36.51Zm5,0H44v2.48H41.48Zm2.48,0h2.48v2.48H44Zm2.48,0h2.48v2.48H46.44Zm2.48,0h2.49v2.48H48.92Zm5,0h2.48v2.48H53.89Zm5,0h2.48v2.48H58.86ZM6.72,31.36H9.2v2.49H6.72Zm2.48,0h2.48v2.49H9.2Zm2.48,0h2.49v2.49H11.68Zm2.49,0h2.48v2.49H14.17Zm9.93,0h2.48v2.49H24.1Zm2.48,0h2.48v2.49H26.58Zm2.48,0h2.48v2.49H29.06Zm2.48,0H34v2.49H31.54Zm7.45,0h2.49v2.49H39Zm2.49,0H44v2.49H41.48Zm2.48,0h2.48v2.49H44Zm7.45,0h2.48v2.49H51.41Zm5,0h2.49v2.49H56.37Zm5,0h2.48v2.49H61.34Zm5,0h2.49v2.49H66.3Zm5,0h2.48v2.49H71.27Zm-67,2.49H6.72v2.48H4.23Zm2.49,0H9.2v2.48H6.72Zm5,0h2.49v2.48H11.68Zm2.49,0h2.48v2.48H14.17Zm2.48,0h2.48v2.48H16.65Zm2.48,0h2.48v2.48H19.13Zm2.48,0H24.1v2.48H21.61Zm5,0h2.48v2.48H26.58Zm5,0H34v2.48H31.54Zm5,0H39v2.48H36.51Zm2.48,0h2.49v2.48H39Zm7.45,0h2.48v2.48H46.44Zm2.48,0h2.49v2.48H48.92Zm5,0h2.48v2.48H53.89Zm12.41,0h2.49v2.48H66.3Zm2.49,0h2.48v2.48H68.79ZM9.2,36.33h2.48v2.48H9.2Zm5,0h2.48v2.48H14.17Zm7.44,0H24.1v2.48H21.61Zm2.49,0h2.48v2.48H24.1Zm2.48,0h2.48v2.48H26.58Zm2.48,0h2.48v2.48H29.06Zm5,0h2.48v2.48H34Zm2.48,0H39v2.48H36.51Zm9.93,0h2.48v2.48H46.44Zm2.48,0h2.49v2.48H48.92Zm2.49,0h2.48v2.48H51.41Zm2.48,0h2.48v2.48H53.89Zm2.48,0h2.49v2.48H56.37Zm2.49,0h2.48v2.48H58.86Zm5,0H66.3v2.48H63.82Zm9.93,0h2.48v2.48H73.75ZM4.23,38.81H6.72v2.48H4.23Zm5,0h2.48v2.48H9.2Zm2.48,0h2.49v2.48H11.68Zm5,0h2.48v2.48H16.65Zm2.48,0h2.48v2.48H19.13Zm2.48,0H24.1v2.48H21.61Zm19.87,0H44v2.48H41.48Zm5,0h2.48v2.48H46.44Zm7.45,0h2.48v2.48H53.89Zm7.45,0h2.48v2.48H61.34Zm5,0h2.49v2.48H66.3Zm2.49,0h2.48v2.48H68.79ZM14.17,41.29h2.48v2.49H14.17Zm2.48,0h2.48v2.49H16.65Zm7.45,0h2.48v2.49H24.1Zm2.48,0h2.48v2.49H26.58Zm2.48,0h2.48v2.49H29.06Zm5,0h2.48v2.49H34Zm14.89,0h2.49v2.49H48.92Zm5,0h2.48v2.49H53.89Zm7.45,0h2.48v2.49H61.34Zm2.48,0H66.3v2.49H63.82Zm7.45,0h2.48v2.49H71.27ZM6.72,43.78H9.2v2.48H6.72Zm2.48,0h2.48v2.48H9.2Zm2.48,0h2.49v2.48H11.68Zm7.45,0h2.48v2.48H19.13Zm5,0h2.48v2.48H24.1Zm9.93,0h2.48v2.48H34Zm2.48,0H39v2.48H36.51Zm2.48,0h2.49v2.48H39Zm2.49,0H44v2.48H41.48Zm2.48,0h2.48v2.48H44Zm2.48,0h2.48v2.48H46.44Zm14.9,0h2.48v2.48H61.34Zm5,0h2.49v2.48H66.3Zm2.49,0h2.48v2.48H68.79ZM4.23,46.26H6.72v2.48H4.23Zm5,0h2.48v2.48H9.2Zm2.48,0h2.49v2.48H11.68Zm2.49,0h2.48v2.48H14.17Zm2.48,0h2.48v2.48H16.65Zm7.45,0h2.48v2.48H24.1Zm2.48,0h2.48v2.48H26.58Zm2.48,0h2.48v2.48H29.06Zm14.9,0h2.48v2.48H44Zm5,0h2.49v2.48H48.92Zm2.49,0h2.48v2.48H51.41Zm2.48,0h2.48v2.48H53.89Zm5,0h2.48v2.48H58.86Zm2.48,0h2.48v2.48H61.34Zm2.48,0H66.3v2.48H63.82Zm5,0h2.48v2.48H68.79Zm5,0h2.48v2.48H73.75ZM4.23,48.74H6.72v2.48H4.23Zm5,0h2.48v2.48H9.2Zm2.48,0h2.49v2.48H11.68Zm7.45,0h2.48v2.48H19.13Zm2.48,0H24.1v2.48H21.61Zm9.93,0H34v2.48H31.54Zm2.49,0h2.48v2.48H34Zm5,0h2.49v2.48H39Zm2.49,0H44v2.48H41.48Zm2.48,0h2.48v2.48H44Zm2.48,0h2.48v2.48H46.44Zm2.48,0h2.49v2.48H48.92Zm12.42,0h2.48v2.48H61.34Zm7.45,0h2.48v2.48H68.79ZM4.23,51.22H6.72v2.49H4.23Zm12.42,0h2.48v2.49H16.65Zm7.45,0h2.48v2.49H24.1Zm2.48,0h2.48v2.49H26.58Zm5,0H34v2.49H31.54Zm7.45,0h2.49v2.49H39Zm2.49,0H44v2.49H41.48Zm2.48,0h2.48v2.49H44Zm9.93,0h2.48v2.49H53.89Zm2.48,0h2.49v2.49H56.37Zm2.49,0h2.48v2.49H58.86Zm2.48,0h2.48v2.49H61.34Zm2.48,0H66.3v2.49H63.82Zm7.45,0h2.48v2.49H71.27Zm-67,2.49H6.72v2.48H4.23Zm5,0h2.48v2.48H9.2Zm2.48,0h2.49v2.48H11.68Zm2.49,0h2.48v2.48H14.17Zm2.48,0h2.48v2.48H16.65Zm2.48,0h2.48v2.48H19.13Zm2.48,0H24.1v2.48H21.61Zm2.49,0h2.48v2.48H24.1Zm5,0h2.48v2.48H29.06Zm2.48,0H34v2.48H31.54Zm5,0H39v2.48H36.51Zm2.48,0h2.49v2.48H39Zm2.49,0H44v2.48H41.48Zm2.48,0h2.48v2.48H44Zm2.48,0h2.48v2.48H46.44Zm5,0h2.48v2.48H51.41Zm2.48,0h2.48v2.48H53.89Zm2.48,0h2.49v2.48H56.37Zm2.49,0h2.48v2.48H58.86Zm2.48,0h2.48v2.48H61.34Zm2.48,0H66.3v2.48H63.82Zm5,0h2.48v2.48H68.79Zm2.48,0h2.48v2.48H71.27Zm2.48,0h2.48v2.48H73.75ZM24.1,56.19h2.48v2.48H24.1Zm2.48,0h2.48v2.48H26.58Zm2.48,0h2.48v2.48H29.06Zm5,0h2.48v2.48H34Zm5,0h2.49v2.48H39Zm5,0h2.48v2.48H44Zm2.48,0h2.48v2.48H46.44Zm2.48,0h2.49v2.48H48.92Zm5,0h2.48v2.48H53.89Zm9.93,0H66.3v2.48H63.82Zm2.48,0h2.49v2.48H66.3Zm2.49,0h2.48v2.48H68.79Zm2.48,0h2.48v2.48H71.27Zm2.48,0h2.48v2.48H73.75ZM4.23,58.67H6.72v2.49H4.23Zm2.49,0H9.2v2.49H6.72Zm2.48,0h2.48v2.49H9.2Zm2.48,0h2.49v2.49H11.68Zm2.49,0h2.48v2.49H14.17Zm2.48,0h2.48v2.49H16.65Zm2.48,0h2.48v2.49H19.13Zm5,0h2.48v2.49H24.1Zm5,0h2.48v2.49H29.06Zm7.45,0H39v2.49H36.51Zm5,0H44v2.49H41.48Zm2.48,0h2.48v2.49H44Zm2.48,0h2.48v2.49H46.44Zm5,0h2.48v2.49H51.41Zm2.48,0h2.48v2.49H53.89Zm5,0h2.48v2.49H58.86Zm5,0H66.3v2.49H63.82Zm2.48,0h2.49v2.49H66.3Zm2.49,0h2.48v2.49H68.79ZM4.23,61.16H6.72v2.48H4.23Zm14.9,0h2.48v2.48H19.13Zm7.45,0h2.48v2.48H26.58Zm2.48,0h2.48v2.48H29.06Zm5,0h2.48v2.48H34Zm5,0h2.49v2.48H39Zm5,0h2.48v2.48H44Zm7.45,0h2.48v2.48H51.41Zm2.48,0h2.48v2.48H53.89Zm9.93,0H66.3v2.48H63.82Zm9.93,0h2.48v2.48H73.75ZM4.23,63.64H6.72v2.48H4.23Zm5,0h2.48v2.48H9.2Zm2.48,0h2.49v2.48H11.68Zm2.49,0h2.48v2.48H14.17Zm5,0h2.48v2.48H19.13Zm5,0h2.48v2.48H24.1Zm2.48,0h2.48v2.48H26.58Zm2.48,0h2.48v2.48H29.06Zm5,0h2.48v2.48H34Zm2.48,0H39v2.48H36.51Zm9.93,0h2.48v2.48H46.44Zm7.45,0h2.48v2.48H53.89Zm2.48,0h2.49v2.48H56.37Zm2.49,0h2.48v2.48H58.86Zm2.48,0h2.48v2.48H61.34Zm2.48,0H66.3v2.48H63.82Zm5,0h2.48v2.48H68.79Zm2.48,0h2.48v2.48H71.27Zm2.48,0h2.48v2.48H73.75ZM4.23,66.12H6.72V68.6H4.23Zm5,0h2.48V68.6H9.2Zm2.48,0h2.49V68.6H11.68Zm2.49,0h2.48V68.6H14.17Zm5,0h2.48V68.6H19.13Zm5,0h2.48V68.6H24.1Zm5,0h2.48V68.6H29.06Zm7.45,0H39V68.6H36.51Zm7.45,0h2.48V68.6H44Zm2.48,0h2.48V68.6H46.44Zm2.48,0h2.49V68.6H48.92Zm2.49,0h2.48V68.6H51.41Zm9.93,0h2.48V68.6H61.34Zm5,0h2.49V68.6H66.3Zm2.49,0h2.48V68.6H68.79Zm2.48,0h2.48V68.6H71.27Zm2.48,0h2.48V68.6H73.75ZM4.23,68.6H6.72v2.49H4.23Zm5,0h2.48v2.49H9.2Zm2.48,0h2.49v2.49H11.68Zm2.49,0h2.48v2.49H14.17Zm5,0h2.48v2.49H19.13Zm5,0h2.48v2.49H24.1Zm7.44,0H34v2.49H31.54Zm2.49,0h2.48v2.49H34Zm2.48,0H39v2.49H36.51Zm2.48,0h2.49v2.49H39Zm2.49,0H44v2.49H41.48Zm2.48,0h2.48v2.49H44Zm9.93,0h2.48v2.49H53.89Zm2.48,0h2.49v2.49H56.37Zm2.49,0h2.48v2.49H58.86Zm2.48,0h2.48v2.49H61.34Zm2.48,0H66.3v2.49H63.82Zm2.48,0h2.49v2.49H66.3Zm2.49,0h2.48v2.49H68.79Zm2.48,0h2.48v2.49H71.27Zm-67,2.49H6.72v2.48H4.23Zm14.9,0h2.48v2.48H19.13Zm5,0h2.48v2.48H24.1Zm2.48,0h2.48v2.48H26.58Zm5,0H34v2.48H31.54Zm7.45,0h2.49v2.48H39Zm5,0h2.48v2.48H44Zm9.93,0h2.48v2.48H53.89Zm5,0h2.48v2.48H58.86Zm2.48,0h2.48v2.48H61.34Zm5,0h2.49v2.48H66.3Zm5,0h2.48v2.48H71.27Zm-67,2.48H6.72v2.48H4.23Zm2.49,0H9.2v2.48H6.72Zm2.48,0h2.48v2.48H9.2Zm2.48,0h2.49v2.48H11.68Zm2.49,0h2.48v2.48H14.17Zm2.48,0h2.48v2.48H16.65Zm2.48,0h2.48v2.48H19.13Zm5,0h2.48v2.48H24.1Zm7.44,0H34v2.48H31.54Zm2.49,0h2.48v2.48H34Zm12.41,0h2.48v2.48H46.44Zm2.48,0h2.49v2.48H48.92Zm2.49,0h2.48v2.48H51.41Zm7.45,0h2.48v2.48H58.86Zm2.48,0h2.48v2.48H61.34Zm2.48,0H66.3v2.48H63.82Zm2.48,0h2.49v2.48H66.3Zm2.49,0h2.48v2.48H68.79Z"
                                        transform="translate(-4.23 -4.05)" style="fill:#010101" />
                                </g>
                            </svg>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="centered" colspan="3">
                        <h2><i class="fa fa-headphones" aria-hidden="true"></i> 010 5009 26 30</h2>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="centered">
                        <i class="fa fa-globe" aria-hidden="true"></i>
                        <span style="font-size: 12px">levoilestores.com</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <script type="text/javascript">
        function auto_print() {
            window.print();
            window.location.href = '<?php echo e(route('prepare.all', ['delivery_status' => 'prepared'])); ?>';
        }
        setTimeout(auto_print, 1500);
    </script>

</body>

</html>
<?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/preparation/invoice_order.blade.php ENDPATH**/ ?>
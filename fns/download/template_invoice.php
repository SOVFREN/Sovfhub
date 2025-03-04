<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{$invoice_title|noescape}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
        }

        .invoice-header {
            text-align: left;
            color: #000;
            padding: 6px;
            border-radius: 5px;
            display: table;
            width: 100%;
        }

        .invoice-header > div {
            display: table-cell;
        }


        .invoice-header > .logo {}



        .invoice-header > .logo > img {
            max-width: 200px;
        }




        .invoice-header > .text {
            text-align: right;
        }




        .invoice-header h1 {
            margin: 0;
            text-transform: uppercase;
            font-size: 50px;
            font-weight: 700;
            color: #dbdbdb;
        }

        .invoice-info {
            display: table;
            margin-top: 0px;
            width: 100%;
        }

        .invoice-id {
            display: table-cell;
        }

        .payment-date {
            display: table-cell;
            text-align: right;
        }

        .address-container {
            display: table;
            margin-top: 20px;
            width: 100%;
            margin-bottom: 25px;
        }

        .client-address, .business-address {
            display: table-cell;
        }
        .client-address p, .business-address p {
            margin: 0px;
            line-height: 26px;
        }
        .client-address > div, .business-address > div {
            background: #ffffff;
        }
        .client-address {
            padding-right: 25px;
        }

        .business-address {
            text-align: right;
        }

        .invoice-details {
            margin-top: 25px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
        }

        .invoice-details th,
        .invoice-details td {
            border: 1px solid #000000;
            padding: 30px 10px;
            text-align: center;
            background: #ecf9ff;
        }

        .invoice-details th {
            background-color: #2196F3;
            color: #ffffff;
            padding: 13px 10px;
        }

        .invoice-total {
            margin-top: 40px;
            width: 100%;
            display: table;
        }

        .invoice-total > div {
            display: table-cell;
            font-size: 18px;
            overflow: hidden;
            text-align: right;
        }

        .invoice-total > div.payment_method {
            text-align: left;
            font-size: 16px;
        }

        .invoice-total > div.payment_method > div > img {
            max-width: 100px;
            margin-top: 5px;
        }
        .invoice-total  > div  > div:last-child {
            background: #ffffff;
            border-left: 0px;
            margin-top: 10px;
            font-size: 30px;
            font-weight: 700;
        }
        .invoice-footer {
            margin-top: 124px;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #d3dee2;
            color: #95a0a9;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="logo">
                <img src="{$site_url|noescape}assets/files/logos/invoice_logo.png" />
            </div>
            <div class="text">
                <h1>{$invoice|noescape}</h1>
            </div>
        </div>
        <div class="address-container">
            <div class="client-address">
                <div>
                    <p>
                        <strong>{$invoice_to|noescape}</strong>
                    </p>
                    <p>
                        {$billed_to|noescape}
                    </p>
                    <p>
                        {$client_address|noescape}
                    </p>
                </div>
            </div>
            <div class="business-address">
                <div>
                    <p>
                        <strong>{$invoice_from|noescape}</strong>
                    </p>
                    <p>
                        {$billed_from|noescape}
                    </p>
                    <p>
                        {$business_address|noescape}
                    </p>
                </div>
            </div>
        </div>
        <div class="invoice-info">
            <div class="invoice-id">
                <p>
                    <strong>{$invoice_id_text|noescape}:</strong> {$invoice_id|noescape}
                </p>
            </div>
            <div class="payment-date">
                <p>
                    <strong>{$date_text|noescape}:</strong> {$order_date|noescape}
                </p>
            </div>
        </div>
        <div class="invoice-details">
            <table>
                <tr>
                    <th>{$order_id_text|noescape}</th>
                    <th>{$description_text|noescape}</th>
                    <th>{$price_text|noescape}</th>
                </tr>
                <tr>
                    <td>{$order_id|noescape}</td>
                    <td>{$order_description|noescape}</td>
                    <td>{$order_price|noescape}</td>
                </tr>
            </table>
        </div>
        <div class="invoice-total">
            <div class="payment_method">
                <div>
                    {$payment_method_text|noescape}
                </div>
                <div>
                    {$payment_method_image|noescape}
                </div>
            </div>
            <div>
                <div class="text">
                    {$invoice_total|noescape}
                </div>
                <div>
                    {$order_price|noescape}
                </div>
            </div>
        </div>
        <div class="invoice-footer">
            <p>
                {$invoice_footer_note|noescape}
            </p>
        </div>
    </div>
</body>
</html>
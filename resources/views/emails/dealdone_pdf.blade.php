<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            padding: 40px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .invoice-header td {
            vertical-align: top;
        }

        .company-name {
            font-weight: bold;
            font-size: 18px;
        }

        .company-address {
            font-size: 12px;
            color: #444;
        }

        .logo img {
            height: 80px;
        }

        .section {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .item-table th,
        .item-table td {
            border-bottom: 1px solid #eee;
            padding: 10px;
            font-size: 13px;
        }

        .totals td {
            padding: 4px 0;
            font-size: 13px;
        }

        .totals td:last-child {
            text-align: right;
        }
    </style>
</head>

<body>

    <table class="invoice-header">
        <tr>
            <td>
                <p class="company-name">PhoneXpress</p>
                <p class="company-address">Brisbane, Sydney, Melbourne<br>ABN : 24 674 768 435</p>
            </td>
            <td class="logo" align="right">
                <img src="https://www.getdemo.in/Phoneexpress/assets/images/logo.png" alt="Logo">
            </td>
        </tr>
    </table>

    <table class="section">
        <tr>
            <td>
                <strong>BILL TO</strong><br>
                <span>{{ $data['Name'] }}</span><br>
                <span>{{ $data['Email'] }}</span>
            </td>
            <td align="right">
                <strong>INVOICE NUMBER:</strong> {{ $data['DealData']['invoiceno'] }}<br>
                <strong>ISSUED:</strong> {{ date('d-m-Y') }}
            </td>
        </tr>
    </table>

    <table class="item-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Amount (Incl. GST)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $data['DealData']['Brand'] }}<br>
                    <span style="font-size: 12px; color:#666;">
                        Model: {{ $data['DealData']['Model'] }}<br>
                        IMEI: {{ $data['DealData']['imei_1'] }}
                    </span>
                </td>
                <td>${{ number_format($data['DealData']['Amount'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals" align="right" width="50%">
        <tr>
            <td>Subtotal (Excl. GST)</td>
            <td>${{ number_format($data['DealData']['Amount'], 2) }}</td>
        </tr>
        <tr>
            <td>GST ({{ $data['DealData']['gst'] }}%)</td>
            <td>${{ number_format($data['DealData']['gst'], 2) }}</td>
        </tr>
        <tr>
            <td><strong>Total (Incl. GST)</strong></td>
            <td><strong>${{ number_format($data['DealData']['total_amount'], 2) }}</strong></td>
        </tr>
    </table>

    <br><br><br>
    <p style="font-size: 12px;">* GST has been included in the above total.</p>

</body>

</html>

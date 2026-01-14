<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voucher</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #eeeeee;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #3a3a3a;
        }

        .layout {
            width: 90%;
            max-width: 750px;
            margin: 18px auto;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden; 
        }

        table.header-layout {
            width: 100%;
            height: 60px;
            background-color: #fff;
            border-collapse: collapse;
        }
        
        table.header-layout td {
            padding: 0 24px;
            vertical-align: middle;
        }

        .logo {
            color: #000;
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .p-24 {
            padding: 24px;
        }
        
        .mb-20 {
            margin-bottom: 20px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .header {
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }

        .small-header {
            font-size: 12px;
            color: #999999;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .divider {
            height: 1px;
            background-color: #ddd;
            margin: 16px 0;
            width: 100%;
        }

        .package-name {
            font-size: 16px;
            font-weight: bold;
        }

        .row {
            width: 100%;
            display: block;
        }

        .row::after {
            content: "";
            display: table;
            clear: both;
        }

        .col-50 {
            width: 50%;
            float: left;
        }

        .col-33 {
            width: 33.33%;
            float: left;
        }

        .ticket-separator {
            position: relative;
            height: 30px;
            background-color: #fff;
            width: 100%;
            overflow: hidden; 
        }

        /* The Line */
        .ticket-separator .dashed-line {
            position: absolute;
            top: 14px;
            left: 0;
            width: 100%;
            border-top: 1.5px dashed #ccc;
            z-index: 1;
        }

        .ticket-separator .circle {
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: #eeeeee;
            border-radius: 50%;
            top: 5px;
            z-index: 2;
        }

        .ticket-separator .circle.left {
            left: -10px;
        }

        .ticket-separator .circle.right {
            right: -10px;
        }

        .center-box {
            text-align: center;
            padding: 30px 0;
        }
    </style>
</head>

<body>
    <div class="layout">
        <table class="header-layout">
            <tr>
                <td>
                    <div class="logo">Meetomorrow</div>
                </td>
                <td style="text-align: right;">
                </td>
            </tr>
        </table>

        <div class="p-24">
            <div class="header">Robinson Crousoe Sunset Cruise Tour with Dinner and Show</div>
            <div class="divider"></div>

            <div>
                <div class="small-header">Package</div>
                <div class="package-name">Join-in Sunset Cruise Tour</div>
            </div>
            <div class="divider"></div>

            <div>
                <div class="row mb-20">
                    <div class="col-50">
                        <div class="small-header">Lead participant</div>
                        <div>Naing Win</div>
                    </div>
                    <div class="col-50">
                        <div class="small-header">Date</div>
                        <div>24 Jan 2026 17:00</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-50">
                        <div class="small-header">Quantity</div>
                        <div>1 x Adult</div>
                    </div>
                    <div class="col-50">
                        <div class="small-header">Booking reference ID</div>
                        <div>ABC123456789</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ticket-separator">
            <div class="circle left"></div>
            <div class="dashed-line"></div>
            <div class="circle right"></div>
        </div>

        <div class="center-box">
            <div>QR Code Placeholder</div>
            <div class="mt-20" style="font-weight: bold; letter-spacing: 1px;">VCH123456789</div>
        </div>
    </div>

    <div class="layout">
        <div class="p-24">
            <div class="header">Traveler's Information</div>
            <div class="divider"></div>

            <div class="row">
                <div class="col-33">
                    <div class="small-header">Name</div>
                    <div>Zen</div>
                </div>
                <div class="col-33">
                    <div class="small-header">Email</div>
                    <div>zen@gmail.com</div>
                </div>
                <div class="col-33">
                    <div class="small-header">Passport Number</div>
                    <div>A12345678</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
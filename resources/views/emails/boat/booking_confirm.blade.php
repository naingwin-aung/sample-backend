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
            max-width: 600px;
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

        .mb-10 {
            margin-bottom: 10px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .ms-25 {
            margin-left: 25px;
        }

        .header {
            font-size: 22px;
            font-weight: bold;
            color: #000;
        }

        .secondary-header {
            font-size: 16px;
            font-weight: bold;
        }

        .third-header {
            font-size: 14px;
            font-weight: bold;
        }

        .small-header {
            font-size: 13px;
            color: #999999;
            margin-bottom: 5px;
        }

        .divider {
            height: 1px;
            background-color: #ddd;
            margin: 16px 0;
            width: 100%;
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

        .col-65 {
            width: 65%;
            float: left;
        }

        .col-30 {
            width: 30%;
            float: left;
        }
        
        .payment-col-65 {
            width: 65%;
            float: left;
        }

        .payment-col-30 {
            width: 30%;
            float: left;
        }

        .payment-ms-25 {
            margin-left: 25px;
        }

        .center-box {
            text-align: center;
            padding: 30px 0;
        }

        @media only screen and (max-width: 480px) {
            body,
            table,
            td,
            p,
            div {
                font-size: 12px !important;
            }

            .header {
                font-size: 18px !important;
            }

            .secondary-header {
                font-size: 14px !important;
            }

            .third-header {
                font-size: 12px !important;
            }

            .col-65,
            .col-30 {
                width: 100% !important;
                float: none !important;
            }

            .ms-25 {
                margin-left: 0 !important;
                margin-top: 10px !important;
            }

            .payment-ms-25 {
                margin-left: 0 !important;
            }
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
            <div class="header">Hey naing win, your booking has been confirmed!</div>
            <br>
            <div>
                Your booking for Robinson Crusoe Sunset Cruise Tour with Dinner and Show is confirmed.
            </div>
            <br>
            <div>
                You can find your voucher in this email. Make sure to check how to redeem the voucher before you go.
            </div>
            <br>
            <div class="divider"></div>
            <br>

            <div class="row mb-10">
                <div class="col-65">
                    <div class="small-header">Booking No: ABC123456789</div>
                </div>
                <div class="col-30">
                    <div class="small-header ms-25">Booked: 2026-01-12</div>
                </div>
            </div>

            <div class="row">
                <div class="col-65">
                    <div class="secondary-header mb-10">Robinson Crusoe Sunset Cruise Tour with Dinner and Show</div>
                    <div class="mb-10">
                        Join-in Sunset Cruise Tour
                    </div>
                    <div class="mb-10">
                        <div class="small-header">Date</div>
                        24 Jan 2026
                    </div>
                    <div class="mb-10">
                        <div class="small-header">Current bookings</div>
                        Adult X 1
                    </div>
                </div>
                <div class="col-30 ms-25">
                    <image src="https://i.pinimg.com/1200x/a7/11/ac/a711ac064512f8677b1ff5bafe1babb5.jpg"
                        alt="Boat Image" style="width: 132px; height: 85px; border-radius: 8px; object-fit: cover;">
                </div>
            </div>

            <div class="divider"></div>

            <div class="row">
                <div class="payment-col-65">
                    <div>Subtotal</div>
                </div>
                <div class="payment-col-30">
                    <div class="payment-ms-25">1,200 THB</div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="payment-col-65">
                    <div class="third-header">Grand Total</div>
                </div>
                <div class="payment-col-30">
                    <div class="payment-ms-25 third-header">1,200 THB</div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="center-box">
                If you have received this communication in error, please do not forward its contents; notify the sender
                and delete it and any attachments. This message may contain information that is confidential and legally
                privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or
                any
                information contained in it.
            </div>
        </div>
    </div>
</body>

</html>

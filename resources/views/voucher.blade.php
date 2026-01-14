<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voucher</title>
</head>
<style>
    body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background: #eeeeee;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
    }

    .header-layout {
        height: 60px;
        padding: 0 24px;
        background-color: #6B3CA9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        color: #fff;
        font-size: 20px;
        font-weight: bold;
    }

    .package-name {
        font-size: 16px;
        font-weight: bold;
    }

    /* utility classes */
    .layout {
        max-width: 750px;
        margin: 25px auto;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
    }

    .divider {
        height: 1px;
        background-color: #ddd;
        margin: 16px 0;
    }

    .dot-divider {
        width: 100%;
        border-bottom: 1px dotted #ddd;
        margin: 18px 0;
    }

    .header {
        font-size: 18px;
        font-weight: bold;
    }

    .small-header {
        font-size: 13px;
        color: #999999;
        margin-bottom: 9px;
    }

    .flex {
        display: flex;
    }

    .w-50 {
        width: 50%;
    }

    .w-33 {
        width: 33.33%;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .p-24 {
        padding: 24px;
    }
</style>

<body>
    <div class="layout">
        <header class="header-layout">
            <div class="logo">
                Logo
            </div>
            <div>
            </div>
        </header>
        <div class="p-24">
            <div class="header">Robinson Crousoe Sunset Cruise Tour with Dinner and Show</div>
            <div class="divider"></div>

            <div class="package">
                <div class="small-header">
                    Package
                </div>
                <div class="package-name">
                    Join-in Sunset Cruise Tour
                </div>
            </div>
            <div class="divider"></div>

            {{-- leader section --}}
            <div class="leader">
                <div class="flex mb-20">
                    <div class="w-50">
                        <div class="small-header">
                            Lead participant
                        </div>
                        <div>
                            Naing Win
                        </div>
                    </div>

                    <div class="w-50">
                        <div class="small-header">
                            Date
                        </div>
                        <div>
                            24 Jan 2026 17:00
                        </div>
                    </div>
                </div>

                <div class="flex">
                    <div class="w-50">
                        <div class="small-header">
                            Quantity
                        </div>
                        <div>
                            1 x Adult
                        </div>
                    </div>

                    <div class="w-50">
                        <div class="small-header">
                            Booking reference ID
                        </div>
                        <div>
                            ABC123456789
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="dot-divider"></div> --}}
        </div>
    </div>

    <div class="layout">
        <div class="p-24">
            <div class="header">Traveler's Information</div>
            <div class="divider"></div>

            <div class="flex">
                <div class="w-33">
                    <div class="small-header">
                        Name
                    </div>
                    <div>
                        Zen
                    </div>
                </div>

                <div class="w-33">
                    <div class="small-header">
                        Email
                    </div>
                    <div>
                        zen@gmail.com
                    </div>
                </div>

                <div class="w-33">
                    <div class="small-header">
                        Passport Number
                    </div>
                    <div>
                        A12345678
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

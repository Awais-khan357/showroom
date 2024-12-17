<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alahlya Used Cars Trading - Receipt Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }

        .voucher-container {
            max-width: 800px;
            margin: 2rem auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            border: 2px solid #0d47a1;
            border-radius: 10px 10px 0 0;
            padding: 1rem;
        }

        .company-name {
            color: #0d47a1;
            font-weight: bold;
        }

        .cars-banner {
            background: linear-gradient(to right, #ffff, #ffff);
            height: 120px;
            margin: 1rem 0;
            background: repeat-x;
            border-radius: 5px;
            position: relative;
            overflow: hidden;
        }

        .receipt-section {
            border: 2px solid #0d47a1;
            border-top: none;
            border-radius: 0 0 10px 10px;
            padding: 1.5rem;
        }

        .amount-box {
            border: 1px solid #0d47a1;
            padding: 0.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .form-label {
            color: #0d47a1;
            font-weight: bold;
        }

        .signature-line {
            border-top: 1px solid #0d47a1;
            margin-top: 2rem;
            padding-top: 0.5rem;
        }

        @media print {
            body {
                background-color: #fff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin-top: 20px;
            }

            .voucher-container {
                max-width: 99%;
                padding-left: 10px;
                padding-right: 10px;
                box-shadow: none;
            }

            .header .row {
                display: flex !important;
                flex-wrap: nowrap !important;

            }

            .header .col-md-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }

            .header .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .header,
            .receipt-section {
                border-color: #000;
            }

            .company-name,
            .form-label {
                color: #0d47a1;

            }

            input[type="text"] {
                border: 1px solid #000;
            }

            .signature-line {
                border-top-color: #000;
            }

            .row {
                display: flex !important;
                align-items: flex-start !important;
            }

            .col-md-3,
            .col-md-6 {
                margin: 0 !important;
                padding: 0 !important;
                page-break-inside: avoid;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>

<?php
include('./includes/dbConfig.php');

if (isset($_GET['invoice_id'])) {
    $invoice_id = $_GET['invoice_id'];

    $invoiceQuery = "
        SELECT i.*, c.name AS customer_name, c.email AS customer_email, c.phone AS customer_phone, c.address AS customer_address, 
               ca.brand AS car_brand, ca.model AS car_model, ca.registration_number AS car_registration, ca.description AS car_description
        FROM `invoices` i
        JOIN `customers` c ON i.customer_id = c.c_id
        JOIN `cars` ca ON i.car_id = ca.car_id
        WHERE i.invoice_id = '$invoice_id'
    ";
    $invoiceResult = $conn->query($invoiceQuery);

    if ($invoiceResult->num_rows > 0) {
        $invoiceData = $invoiceResult->fetch_assoc();
    } else {
        echo "<script>alert('Invoice not found!');</script>";
        die();
    }
} else {
    echo "<script>alert('No invoice ID provided!');</script>";
    die();
}
?>

<body>
    <div class="voucher-container">
        <!-- Header Section -->
        <div class="header">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div>Mob: 056-1719111</div>
                    <div>Mob: 056-6661350</div>
                </div>
                <div class="col-md-6 text-center">
                    <h1 class="company-name">الاهلية لتجارة السيارات المستعملة</h1>
                    <h2 class="company-name">ALAHLYA USED CARS TRADING</h2>
                </div>
                <div class="col-md-3">
                    <div>متحرك: ٠٥٦-١٧١٩١١١</div>
                    <div>متحرك: ٠٥٦-٦٦٦١٣٥٠</div>
                </div>
            </div>
            <div class="row">
                <div class="col-3 mt-5">
                    <div>Ras Al Khaimah,</div>
                    <div>United Arab Emirates</div>
                </div>
                <div class="col-6 cars-banner">
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRNjogObdUTDO1Rc7ZIwgL4eQwfZsSX3MpHMg&s">
                </div>
                <div class="col-3 mt-5 text-end">
                    <div>رأس الخيمة</div>
                    <div>الإمارات العربية المتحدة</div>
                </div>
            </div>
        </div>

        <!-- Receipt Section -->
        <div class="receipt-section">
            <div class="row mb-3">
                <div class="col-4">
                    <div class="amount-box">
                        <div class="row">
                            <div class="col-8">
                                <label>Dhs. درهم</label>
                                <input type="text" class="form-control" value="<?= $invoiceData['paid_amount']; ?>"
                                    readonly>
                            </div>
                            <div class="col-4">
                                <label>Fils. فلس</label>
                                <input type="text" class="form-control" value="00" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <h3>سند قبض</h3>
                    <h4>RECEIPT VOUCHER</h4>
                </div>
                <div class="col-4 text-end">
                    <div>
                        <label class="form-label">No. / الرقم</label>
                        <input type="text" class="form-control" value="<?= $invoiceData['invoice_id']; ?>" readonly>
                    </div>
                    <div>
                        <label class="form-label">Date / التاريخ</label>
                        <input type="text" class="form-control"
                            value="<?= date('Y-m-d', strtotime($invoiceData['created_at'])); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-8">
                    <label class="form-label">Customer Details / تفاصيل العميل</label>
                    <input type="text" class="form-control" value="<?= $invoiceData['customer_name']; ?>" readonly>
                </div>
                <div class="col-4">
                    <label class="form-label">Customer Mob. / متحرك العميل</label>
                    <input type="text" class="form-control" value="<?= $invoiceData['customer_phone']; ?>" readonly>
                </div>
            </div>

            <!-- Car Details -->
            <div class="mb-3">
                <label class="form-label">Car Details / تفاصيل السيارة</label>
                <input type="text" class="form-control"
                    value="<?= $invoiceData['car_brand'] . ' ' . $invoiceData['car_model']; ?>" readonly>
            </div>

            <!-- Total Amount and Remaining Amount in a Single Row -->
            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label">The Sum of DHS. / مبلغ وقدره فقط</label>
                    <input type="text" class="form-control" value="<?= $invoiceData['total_amount']; ?>" readonly>
                </div>
                <div class="col-6">
                    <label class="form-label">Remaining Amount / المبلغ المتبقي</label>
                    <input type="text" class="form-control" value="<?= $invoiceData['due_amount']; ?>" readonly>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="row mb-3">
                <div class="col-8">
                    <label class="form-label">By Cash/Cheque No. / نقداً/شيك رقم</label>
                    <input type="text" class="form-control" value="نقدي" readonly>
                </div>
                <div class="col-4">
                    <label class="form-label">Date / بتاريخ</label>
                    <input type="text" class="form-control" value="<?= date('Y-m-d'); ?>" readonly>
                </div>
            </div>

            <div class="row signature-line">
                <div class="col-6 text-center">
                    <p>Receiver's Sign. / توقيع المستلم</p>
                    <div class="mt-4">_______________________</div>
                </div>
                <div class="col-6 text-center">
                    <p>Signature / التوقيع</p>
                    <div class="mt-4">_______________________</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
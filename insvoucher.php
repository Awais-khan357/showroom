<?php
session_start();
ob_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}


?>

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
    SELECT 
        c.name AS customer_name,
        c.customer_id AS customer_id,
        car.brand AS car_name,
        car.hss_no AS hss_number,
        p.payment_id AS installment_id,
        p.installment_no,
        p.amount AS installment_amount,
        p.paid_amount,
        p.pay_status,
        p.payment_date,
        p.status AS payment_status
    FROM 
        invoices i
    INNER JOIN 
        customers c ON i.customer_id = c.c_id
    INNER JOIN 
        cars car ON i.car_id = car.car_id
    INNER JOIN 
        payments p ON i.invoice_id = p.invoice_id
    WHERE 
        i.invoice_id = '$invoice_id'
    ORDER BY 
        p.payment_date DESC;
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



$query = "SELECT COUNT(`installment_no`) AS `paid_installments` FROM `payments` WHERE `status` = 'paid'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$paidInstallments = $row['paid_installments'];


?>

<body>
<div class="voucher-container">
    <!-- Header Section -->
    <div class="header">
            <div class="row align-items-center">
              <img src="./banner.jpg" alt="banner" classname="img-fluid"/>
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
                            <input type="text" class="form-control" value="<?= $invoiceData['installment_amount']; ?>" readonly>
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
                    <input type="text" class="form-control" value="<?= $invoiceData['installment_id']; ?>" readonly>
                </div>
                <div>
                    <label class="form-label">Date / التاريخ</label>
                    <input type="text" class="form-control" value="<?= date('Y-m-d', strtotime($invoiceData['payment_date'])); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-8">
                <label class="form-label">Customer Details / تفاصيل العميل</label>
                <input type="text" class="form-control" value="<?= $invoiceData['customer_name']; ?>" readonly>
            </div>
            <div class="col-4">
                <label class="form-label">Customer ID. / متحرك العميل</label>
                <input type="text" class="form-control" value="<?= $invoiceData['customer_id']; ?>" readonly>
            </div>
        </div>

        <!-- Car Details -->
        <div class="mb-3">
            <label class="form-label">Car Details / تفاصيل السيارة</label>
            <input type="text" class="form-control" value="<?= $invoiceData['car_name'] . ' - ' . $invoiceData['hss_number']; ?>" readonly>
        </div>

        <!-- Total Amount and Remaining Amount in a Single Row -->
        <div class="row mb-3">
            <div class="col-4">
                <label class="form-label">The Sum of DHS. / مبلغ وقدره فقط</label>
                <input type="text" class="form-control" value="<?= $invoiceData['installment_amount']; ?>" readonly>
            </div>
            <div class="col-5">
                <label class="form-label">Remaining Amount / المبلغ المتبقي</label>
                <input type="text" class="form-control" value="00" readonly>
            </div>
            <div class="col-3">
    <label class="form-label">Installment No / رقم القسط</label>
    <input type="text" class="form-control" value="<?= $paidInstallments; ?>" readonly>
</div>
        </div>

        <!-- Payment Details -->
        <div class="row mb-3">
            <div class="col-8">
                <label class="form-label">By Cash/Cheque No. / نقداً/شيك رقم</label>
                <input type="text" class="form-control" value="<?= $invoiceData['pay_status']; ?>" readonly>
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
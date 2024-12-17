<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}


?>


<?php
include('./includes/header.php');
include('./includes/dbConfig.php');

if (isset($_POST['delete_invoice'])) {
    $invoice_id = $_POST['invoice_id'];

    $deleteQuery = "DELETE FROM `invoices` WHERE `invoice_id` = '$invoice_id'";
    if ($conn->query($deleteQuery)) {
        echo "<script>alert('Invoice deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting invoice: {$conn->error}');</script>";
    }
}
?>

<body>
    <!-- Layout Wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include('./includes/sidebar.php'); ?>

            <div class="layout-page">
                <?php include('./includes/navbar.php'); ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Invoices الجداول
                            / </span>الفواتير</h4>

                        <!-- Invoices Table -->
                        <div class="card">
                            <h5 class="card-header">Invoices/الفواتير</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SNo / الرقم</th>
                                            <th>Customer / العميل</th>
                                            <th>Customer ID / العميل</th>
                                            <th>Car / السيارة</th>
                                            <th>Hss No  / السيارة</th>
                                            <th>Installment Amount / المبلغ الإجمالي</th>
                                            <th>Paid / المدفوع</th>
                                            <th>Status / المدفوع</th>
                                            <th>Actions / الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
    $query = "
        SELECT 
            i.invoice_id,
            c.name AS customer_name,
            c.c_id AS customer_id,
            car.brand AS car_name,
            car.registration_number AS hss_number,
            p.payment_id AS installment_id,
            p.installment_no,
            p.amount AS installment_amount,
            p.paid_amount,
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
        ORDER BY 
            p.payment_date DESC";
    $result = $conn->query($query);
    $sno = 1;

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
    <td>{$sno}</td>
    <td>{$row['customer_name']}</td>
    <td>{$row['customer_id']}</td>
    <td>{$row['car_name']}</td>
    <td>{$row['hss_number']}</td>
    <td>{$row['installment_amount']}</td>
    <td>{$row['paid_amount']}</td>
    <td>";

if ($row['payment_status'] === 'Paid') {
    echo "<span class='btn btn-secondary'>Paid</span>";
} else {
    echo "<span class='btn btn-warning'>Pending</span>";
}

echo "</td>
<td>";

if ($row['payment_status'] === 'Paid') {
    echo "<a href='insvoucher.php?invoice_id={$row['invoice_id']}' class='btn btn-primary'>Voucher</a>";
} else {
    echo "";
}

echo "</td>
</tr>";

        $sno++;
    }
    ?>
</tbody>

                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('./includes/footer.php'); ?>
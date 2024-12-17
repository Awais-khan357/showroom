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
                                            <th>Car / السيارة</th>
                                            <th>Total Amount / المبلغ الإجمالي</th>
                                            <th>Paid / المدفوع</th>
                                            <th>Due / المستحق</th>
                                            <th>Status / الحالة</th>
                                            <th>Actions / الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "
            SELECT i.invoice_id, c.name AS customer_name, ca.brand AS car_brand, ca.model AS car_model, 
                   i.total_amount, i.paid_amount, i.due_amount, i.status 
            FROM `invoices` i
            JOIN `customers` c ON i.customer_id = c.c_id
            JOIN `cars` ca ON i.car_id = ca.car_id";
                                        $result = $conn->query($query);
                                        $sno = 1;

                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
    <td>{$sno}</td>
    <td>{$row['customer_name']}</td>
    <td>{$row['car_brand']} - {$row['car_model']}</td>
    <td>{$row['total_amount']}</td>
    <td>{$row['paid_amount']}</td>
    <td>{$row['due_amount']}</td>";

if ($row['status'] === 'Partial payment') {
    echo "<td><a  href='installment.php?invoice_id={$row['invoice_id']}' style='color:white;  padding:9px; background-color:gray; border-radius:6px'>Installment</a></td>";
} elseif ($row['status'] === 'Paid') {
    echo "<td>Paid</td>";
} else {
    echo "<td>{$row['status']}</td>";
}

echo "<td>
    <a href='voucher.php?invoice_id={$row['invoice_id']}' class='btn btn-primary'>Voucher</a>
    <form method='POST' style='display:inline-block;'>
        <input type='hidden' name='invoice_id' value='{$row['invoice_id']}'>
        <button type='submit' name='delete_invoice' class='btn btn-danger'>Delete</button>
    </form>
</td>
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
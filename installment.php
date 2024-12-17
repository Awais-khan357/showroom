<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}


?>

<?php

include('./includes/dbConfig.php');


include('./includes/header.php');

if (isset($_GET['invoice_id']) && !empty($_GET['invoice_id'])) {
    $invoice_id = intval($_GET['invoice_id']); 
} else {
    die("<div class='alert alert-danger'>No invoice ID provided!</div>");
}

if (isset($_POST['submit'])) {
    $installment_no = $_POST['inst'];
    $paid_amount = $_POST['paid_amount'];
    $payment_date = date('Y-m-d');

    $update_sql = "UPDATE payments 
                   SET status = 'paid',
                       paid_amount = ?,
                       payment_date = ?,
                       updated_at = NOW()
                   WHERE invoice_id = ? 
                   AND installment_no = ? 
                   AND status = 'pending'";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("dsii", $paid_amount, $payment_date, $invoice_id, $installment_no);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $update_invoice_sql = "UPDATE invoices 
                               SET paid_amount = paid_amount + ?, 
                                   updated_at = NOW() 
                               WHERE invoice_id = ?";
        $stmt3 = $conn->prepare($update_invoice_sql);
        $stmt3->bind_param("di", $paid_amount, $invoice_id);

        if ($stmt3->execute()) {
            echo "<div class='alert alert-success'>Payment successfully recorded!</div>";
            header("Location: viewinstallment.php");
        } else {
            echo "<div class='alert alert-danger'>Error updating invoice: " . $stmt3->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>No rows updated in payments. Check data integrity.</div>";
    }

    $stmt->close();
}


$sql = "
    SELECT 
        c.name AS customer_name,
        c.c_id AS customer_id,
        c.address AS customer_address,
        car.brand AS car_name,
        car.model AS model,
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
    WHERE 
        i.invoice_id = '$invoice_id' 
        AND p.status = 'pending'
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}
?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php

            include('./includes/sidebar.php');

            ?>

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include('./includes/navbar.php');
                ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Basic Layout -->
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-xl-10">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><?php echo $invoice_id ?> </h5>
                                        <small class="text-muted float-end">Merged input group</small>
                                    </div>
                                    <div class="card-body">
                                    <form method="POST">
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label" for="customerName">Customer Name</label>
            <div class="input-group input-group-merge">
                <span id="customerNameIcon" class="input-group-text"><i class="bx bx-user"></i></span>
                <input type="text" class="form-control" id="customerName" name="customer_name" value="<?php echo $row['customer_name']; ?>" placeholder="Customer Name" required />
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="customerId">Customer ID</label>
            <div class="input-group input-group-merge">
                <span id="customerIdIcon" class="input-group-text"><i class="bx bx-id-card"></i></span>
                <input type="text" class="form-control" id="customerId" name="customer_id" value="<?php echo $row['customer_id']; ?>" placeholder="Customer ID" required />
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label" for="customerAddress">Customer Address</label>
            <div class="input-group input-group-merge">
                <span id="customerAddressIcon" class="input-group-text"><i class="bx bx-map"></i></span>
                <input type="text" class="form-control" id="customerAddress" name="customer_address" value="<?php echo $row['customer_address']; ?>" placeholder="Customer Address" required />
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="carName">Car Name</label>
            <div class="input-group input-group-merge">
                <span id="carNameIcon" class="input-group-text"><i class="bx bx-car"></i></span>
                <input type="text" class="form-control" id="carName" name="car_name" value="<?php echo $row['car_name']; ?>" placeholder="Car Name" required />
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label" for="model">Model</label>
            <div class="input-group input-group-merge">
                <span id="modelIcon" class="input-group-text"><i class="bx bx-car"></i></span>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo $row['model']; ?>" placeholder="Model" required />
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="hssNumber">HSS Number</label>
            <div class="input-group input-group-merge">
                <span id="hssNumberIcon" class="input-group-text"><i class="bx bx-barcode"></i></span>
                <input type="text" class="form-control" id="hssNumber" name="hss_number" value="<?php echo $row['hss_number']; ?>" placeholder="HSS Number" required />
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label" for="installmentAmount">Installment Amount</label>
            <div class="input-group input-group-merge">
                <span id="installmentAmountIcon" class="input-group-text"><i class="bx bx-dollar"></i></span>
                <input type="number" class="form-control" id="installmentAmount" name="installment_amount" value="<?php echo $row['installment_amount']; ?>" placeholder="Installment Amount" step="0.01" required />
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="paidAmount">Paid Amount</label>
            <div class="input-group input-group-merge">
                <span id="paidAmountIcon" class="input-group-text"><i class="bx bx-dollar-circle"></i></span>
                <input type="number" class="form-control" id="paidAmount" name="paid_amount" value="<?php echo $row['paid_amount']; ?>" placeholder="Paid Amount" step="0.01" required />
            </div>
        </div>
        <div class="col-md-6 mt-3">
    <div class="mb-3">
        <label for="durationSelect" class="form-label">Installment No</label>
        <select class="form-select" name="inst" aria-label="Installment Select" required>
    <option selected disabled>Select Installment</option>
    <?php
    $qt = "SELECT installment_no FROM payments WHERE invoice_id = '$invoice_id' AND status = 'pending'";
    $smt = $conn->query($qt);
    
    while($res = $smt->fetch_assoc()) {
        $installment_no = $res['installment_no'];
        echo "<option value=\"$installment_no\">Installment $installment_no</option>";
    }
    ?>
</select>
    </div>
</div>
        </div>


    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , made with ❤️ by
                                <a href="" class="footer-link fw-bolder">ThemeSelection</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <?php

    include('./includes/footer.php');


    ?>

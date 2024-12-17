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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customer_id = $_POST['customer_id'];
    $car_id = $_POST['car_id'];
    $total_amount = $_POST['total'];
    $paid_amount = $_POST['paid_amount'];
    $description = "Invoice for car purchase";
    $status = ($total_amount == $paid_amount) ? 'Paid' : 'Pending';
    $due_amount = $total_amount - $paid_amount;
    $installments = $_POST['installments'];
    $duration = $_POST['duration'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    $invoice_query = "INSERT INTO invoices (customer_id, car_id, total_amount, paid_amount, description, status, due_amount, created_at, updated_at)
                      VALUES ('$customer_id', '$car_id', '$total_amount', '$paid_amount', '$description', '', '', '$created_at', '$updated_at')";
    if ($conn->query($invoice_query) === TRUE) {
        $invoice_id = $conn->insert_id;

        if ($installments > 0) {
            $installment_amount = $due_amount / $installments;
            $current_date = date('Y-m-d');

            for ($i = 1; $i <= $installments; $i++) {
                $due_date = date('Y-m-d', strtotime("+$i month", strtotime($current_date)));
                $payment_query = "INSERT INTO payments (invoice_id, installment_no, due_date, amount, paid_amount, payment_date, status, created_at, updated_at)
                                  VALUES ('$invoice_id', '$i', '$installment_amount', '$installment_amount', 0,'', 'Pending', '$created_at', '$updated_at')";

                if (!$conn->query($payment_query)) {
                    echo "Error: " . $payment_query . "<br>" . $conn->error;
                }
            }
        }

        header("Location: viewInvoice.php?submitted Sucessfully");
    } else {
        echo "Error: " . $invoice_query . "<br>" . $conn->error;
    }

}



$customerQuery = "SELECT c_id, name, phone, address FROM customers ORDER BY name Desc";
$customerResult = $conn->query($customerQuery);

$productQuery = "SELECT car_id, brand, model, price FROM cars ORDER BY car_id Desc";
$productResult = $conn->query($productQuery);


$conn->close();

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
                            <div class="col-md-12 col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Add Customers</h5>
                                        <small class="text-muted float-end">Merged input group</small>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="invoice.php">
                                            <!-- Customer Selection -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label for="customerSelect" class="form-label">Select Customer /
                                                            اختر العميل</label>
                                                        <select class="form-select" name="customer_id"
                                                            id="customer-select">
                                                            <option value="">Select Customer </option>
                                                            <?php
                                                            if ($customerResult && $customerResult->num_rows > 0) {
                                                                while ($customer = $customerResult->fetch_assoc()) {
                                                                    echo "<option value='" . $customer['c_id'] . "' data-phone='" . $customer['phone'] . "' data-address='" . $customer['address'] . "'>"
                                                                        . htmlspecialchars($customer['name']) .
                                                                        "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="contact">Contact /
                                                            الاتصال</label>
                                                        <input type="text" id="contact-input" class="form-control"
                                                            id="contact" name="contact" placeholder="Contact" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="address">Address /
                                                            العنوان</label>
                                                        <input type="text" id="address-input" class="form-control"
                                                            id="address" name="address" placeholder="Address " required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="date">Date / التاريخ</label>
                                                        <input type="date" class="form-control" id="date" name="date"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label for="carSelect" class="form-label">Select Car / اختر
                                                            السيارة</label>
                                                        <select class="form-select" id="carSelect" name="car_id"
                                                            aria-label="Default select example">
                                                            <option selected disabled>Select a car</option>
                                                            <?php
                                                            while ($row = $productResult->fetch_assoc()) {
                                                                echo "<option value='" . htmlspecialchars($row['car_id']) . "' 
            data-price='" . htmlspecialchars($row['price']) . "' 
            data-model='" . htmlspecialchars($row['model']) . "'>"
                                                                    . htmlspecialchars($row['brand']) .
                                                                    "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="price">Price / السعر</label>
                                                        <input type="text" class="form-control" id="price" name="price"
                                                            placeholder="Price" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="model">Model / الطراز</label>
                                                        <input type="text" class="form-control" id="model" name="model"
                                                            placeholder="Model" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="total">Total / المجموع</label>
                                                        <input type="text" class="form-control" id="total" name="total"
                                                            placeholder="Total " readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Additional Fields -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="paid_amount">Paid Amount / المبلغ
                                                            المدفوع</label>
                                                        <input type="text" class="form-control" id="paid_amount"
                                                            name="paid_amount" placeholder="Paid Amount " required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="remaining_amount">Remaining
                                                            Amount / المبلغ المتبقي</label>
                                                        <input type="text" class="form-control" id="remaining_amount"
                                                            name="remaining_amount" placeholder="Remaining Amount "
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="installments">Installments /
                                                            الأقساط</label>
                                                        <input type="number" class="form-control" id="installments"
                                                            name="installments" placeholder="Installments" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label for="durationSelect" class="form-label">Select Duration /
                                                            اختر المدة</label>
                                                        <select class="form-select" id="durationSelect" name="duration"
                                                            aria-label="Default select example">
                                                            <option selected disabled>Select duration
                                                            </option>
                                                            <option value="1">One Month / شهر واحد</option>
                                                            <option value="2">Two Months / شهرين</option>
                                                            <option value="3">Three Months / ثلاثة أشهر</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" name="submit" class="btn btn-primary">Add Invoice /
                                                إضافة الفاتورة</button>
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

    <script>
        const totalInput = document.getElementById('total');
        const paidInput = document.getElementById('paid_amount');
        const remainingInput = document.getElementById('remaining_amount');

        paidInput.addEventListener('input', function () {
            const total = parseFloat(totalInput.value) || 0;
            const paid = parseFloat(this.value) || 0;
            const remaining = total - paid;

            remainingInput.value = remaining >= 0 ? remaining.toFixed(2) : 0;
        });

        const customerSelect = document.getElementById('customer-select');
        const contactInput = document.getElementById('contact-input');
        const addressInput = document.getElementById('address-input');

        customerSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const phone = selectedOption.getAttribute('data-phone');
            const address = selectedOption.getAttribute('data-address');

            contactInput.value = phone || '';
            addressInput.value = address || '';
        });

        const carSelect = document.getElementById('carSelect');
        const priceInput = document.getElementById('price');
        const modelInput = document.getElementById('model');

        carSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const model = selectedOption.getAttribute('data-model');

            priceInput.value = price || '';
            modelInput.value = model || '';
            totalInput.value = price || '';
        });
    </script>


    <?php

    include('./includes/footer.php');


    ?>
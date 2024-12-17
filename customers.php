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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    try {
        $query = $conn->prepare("INSERT INTO customers (name, email, phone, address, created_at, updated_at) 
                                VALUES (?, ?, ?, ?, ?,?)");

        $query->bind_param("ssssss", $name, $email, $phone, $address, $created_at, $updated_at);

        if ($query->execute()) {
            header("Location: customerView.php?submitted Sucessfully");
        } else {
            echo "<div class='alert alert-danger'>Error adding customer: " . $conn->error . "</div>";
        }

        $query->close();
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
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
                                        <h5 class="mb-0">Add Customers / إضافة العملاء</h5>
                                        <small class="text-muted float-end">Merged input group / مجموعة الإدخال
                                            المدمجة</small>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="customers.php">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Full Name /
                                                    الاسم الكامل</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                                            class="bx bx-user"></i></span>
                                                    <input type="text" class="form-control"
                                                        id="basic-icon-default-fullname" placeholder="John Doe / جون دو"
                                                        aria-label="John Doe"
                                                        aria-describedby="basic-icon-default-fullname2" name="name" />
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-email">Email / البريد
                                                    الإلكتروني</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                                    <input type="text" id="basic-icon-default-email"
                                                        class="form-control" placeholder="john.doe / جون دو"
                                                        aria-label="john.doe"
                                                        aria-describedby="basic-icon-default-email2" name="email" />
                                                    <span id="basic-icon-default-email2"
                                                        class="input-group-text">@example.com</span>
                                                </div>
                                                <div class="form-text">You can use letters, numbers & periods / يمكنك
                                                    استخدام الحروف والأرقام والفترات</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-phone">Phone No / رقم
                                                    الهاتف</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-phone2" class="input-group-text"><i
                                                            class="bx bx-phone"></i></span>
                                                    <input type="text" id="basic-icon-default-phone"
                                                        class="form-control phone-mask" placeholder="658 799 8941"
                                                        aria-label="658 799 8941"
                                                        aria-describedby="basic-icon-default-phone2" name="phone" />
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-company">Address /
                                                    العنوان</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-company2" class="input-group-text"><i
                                                            class="bx bx-buildings"></i></span>
                                                    <input type="text" id="basic-icon-default-company"
                                                        class="form-control" placeholder="ACME Inc. / إيه سي إم إي"
                                                        aria-label="ACME Inc."
                                                        aria-describedby="basic-icon-default-company2" name="address" />
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-message">Description /
                                                    الوصف</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-message2" class="input-group-text"><i
                                                            class="bx bx-comment"></i></span>
                                                    <textarea id="basic-icon-default-message" name="description"
                                                        class="form-control"
                                                        placeholder="Hi, Do you have something to say? / مرحبا، هل لديك شيء لتقوله؟"
                                                        aria-label="Hi, Do you have a moment to talk Joe?"
                                                        aria-describedby="basic-icon-default-message2"></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-primary">Send /
                                                إرسال</button>
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
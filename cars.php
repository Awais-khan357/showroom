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
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $price = trim($_POST['price']);
    $registration_number = trim($_POST['registration_number']);
    $description = trim($_POST['description']);
    $created_at = date('Y-m-d H:i:s');

    try {
        $query = $conn->prepare("INSERT INTO cars (brand, model, price, registration_number, description, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?)");

        $query->bind_param("sssdss", $brand, $model, $price, $registration_number, $description, $created_at);

        if ($query->execute()) {
            header("Location: carsView.php?submitted=Successfully");
        } else {
            echo "<div class='alert alert-danger'>Error adding car: " . $conn->error . "</div>";
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
                                        <h5 class="mb-0">Add Customers</h5>
                                        <small class="text-muted float-end">Merged input group</small>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="cars.php">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-brand">Brand / العلامة
                                                    التجارية</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-brand2" class="input-group-text"><i
                                                            class="bx bx-car"></i></span>
                                                    <input type="text" class="form-control"
                                                        id="basic-icon-default-brand" placeholder="Brand"
                                                        aria-label="Brand" aria-describedby="basic-icon-default-brand2"
                                                        name="brand" required />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-model">Model /
                                                    الموديل</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-model2" class="input-group-text"><i
                                                            class="bx bx-car"></i></span>
                                                    <input type="text" class="form-control"
                                                        id="basic-icon-default-model" placeholder="Model"
                                                        aria-label="Model" aria-describedby="basic-icon-default-model2"
                                                        name="model" required />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-price">Price /
                                                    السعر</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-price2" class="input-group-text"><i
                                                            class="bx bx-dollar"></i></span>
                                                    <input type="number" class="form-control"
                                                        id="basic-icon-default-price" placeholder="Price"
                                                        aria-label="Price" aria-describedby="basic-icon-default-price2"
                                                        name="price" step="0.01" required />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"
                                                    for="basic-icon-default-registration_number">Registration Number /
                                                    رقم التسجيل</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-registration_number2"
                                                        class="input-group-text"><i class="bx bx-id-card"></i></span>
                                                    <input type="text" class="form-control"
                                                        id="basic-icon-default-registration_number"
                                                        placeholder="Registration Number"
                                                        aria-label="Registration Number"
                                                        aria-describedby="basic-icon-default-registration_number2"
                                                        name="registration_number" required />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"
                                                    for="basic-icon-default-description">Description / الوصف</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-description2"
                                                        class="input-group-text"><i class="bx bx-comment"></i></span>
                                                    <textarea class="form-control" id="basic-icon-default-description"
                                                        name="description" placeholder="Car Description"
                                                        required></textarea>
                                                </div>
                                            </div>

                                            <button type="submit" name="submit" class="btn btn-primary">Add Car / إضافة
                                                سيارة</button>
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
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


$query = "
    SELECT 
        SUM(total_amount) AS total_amount,
        SUM(paid_amount) AS paid_amount,
        SUM(due_amount) AS remaining
    FROM 
        invoices;
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $totalAmount = $data['total_amount'];
    $paidAmount = $data['paid_amount'];
    $remaining_amount = $data['remaining'];
} else {
    $totalAmount = $paidAmount = $profit = 0;
}

$conn->close();

?>




<body>
    <!-- Layout wrapper -->

    <?php if ($_SESSION['lang'] === 'arabic') { ?>


        <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- قائمة -->
        <?php include('./includes/sidebar.php'); ?>

        <!-- / قائمة -->

        <!-- حاوية التخطيط -->
        <div class="layout-page">
            <!-- شريط التنقل -->


            <?php include('./includes/navbar.php'); ?>

            <!-- / شريط التنقل -->

            <!-- غلاف المحتوى -->
            <div class="content-wrapper">
                <!-- المحتوى -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="col-lg-8 mb-4 order-0">
                            <div class="card">
                                <div class="d-flex align-items-end row">
                                    <div class="col-sm-7">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">تهانينا
                                                <?php echo $_SESSION['username']; ?> 🎉
                                            </h5>
                                            <p class="mb-4">
                                                لقد حققت <span class="fw-bold">72%</span> مبيعات أكثر اليوم. تحقق من الشارة الجديدة
                                                في ملفك الشخصي.
                                            </p>

                                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">عرض الشارات</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 text-center text-sm-left">
                                        <div class="card-body pb-0 px-0 px-md-4">
                                            <img src="./assets/img/illustrations/man-with-laptop-light.png"
                                                height="140" alt="عرض شارة المستخدم"
                                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                                data-app-light-img="illustrations/man-with-laptop-light.png" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 order-1">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div
                                                class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img src="./assets/img/icons/unicons/chart-success.png"
                                                        alt="نجاح الرسم البياني" class="rounded" />
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" id="cardOpt3"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="cardOpt3">
                                                        <a class="dropdown-item" href="javascript:void(0);">عرض المزيد</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">حذف</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block mb-1">إجمالي المبلغ</span>
                                            <h5 class="card-title mb-2"><?php echo $totalAmount; ?>
                                            </h5>
                                            <small class="text-success fw-semibold"><i
                                                    class="bx bx-up-arrow-alt"></i> +72.80%</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div
                                                class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img src="./assets/img/icons/unicons/wallet-info.png"
                                                        alt="بطاقة ائتمان" class="rounded" />
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" id="cardOpt6"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="cardOpt6">
                                                        <a class="dropdown-item" href="javascript:void(0);">عرض المزيد</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">حذف</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <span>المبيعات</span>
                                            <h5 class="card-title text-nowrap mb-1">
                                                <?php echo $paidAmount; ?>
                                            </h5>
                                            <small class="text-success fw-semibold"><i
                                                    class="bx bx-up-arrow-alt"></i> +28.42%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- إجمالي الإيرادات -->
                        <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                            <div class="card">
                                <div class="row row-bordered g-0">
                                    <div class="col-md-8">
                                        <h5 class="card-header m-0 me-2 pb-3">إجمالي الإيرادات</h5>
                                        <div id="totalRevenueChart" class="px-2"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                        type="button" id="growthReportId" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        2022
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="growthReportId">
                                                        <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">2019</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="growthChart"></div>
                                        <div class="text-center fw-semibold pt-3 mb-2">62% نمو الشركة</div>

                                        <div
                                            class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                            <div class="d-flex">
                                                <div class="me-2">
                                                    <span class="badge bg-label-primary p-2"><i
                                                            class="bx bx-dollar text-primary"></i></span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <small>2022</small>
                                                    <h6 class="mb-0">$32.5k</h6>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="me-2">
                                                    <span class="badge bg-label-info p-2"><i
                                                            class="bx bx-wallet text-info"></i></span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <small>2021</small>
                                                    <h6 class="mb-0">$41.2k</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ إجمالي الإيرادات -->
                        <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div
                                                class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img src="./assets/img/icons/unicons/paypal.png"
                                                        alt="بطاقة ائتمان" class="rounded" />
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" id="cardOpt4"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="cardOpt4">
                                                        <a class="dropdown-item" href="javascript:void(0);">عرض المزيد</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">حذف</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="d-block mb-1">المدفوعات</span>
                                            <h5 class="card-title text-nowrap mb-2"> <?php echo $paidAmount; ?>
                                            </h5>
                                            <small class="text-danger fw-semibold"><i
                                                    class="bx bx-down-arrow-alt"></i> -14.82%</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div
                                                class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img src="./assets/img/icons/unicons/cc-primary.png"
                                                        alt="نجاح الرسم البياني" class="rounded" />
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" id="cardOpt1"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="cardOpt1">
                                                        <a class="dropdown-item" href="javascript:void(0);">عرض المزيد</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">حذف</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <span>إجمالي الإيرادات</span>
                                            <h5 class="card-title mb-2"> <?php echo $totalAmount; ?>
                                            </h5>
                                            <small class="text-success fw-semibold"><i
                                                    class="bx bx-up-arrow-alt"></i> +28.14%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /المحتوى -->
                        <!-- / حاوية -->
                    </div>
                </div>
            </div>


    <?php } else { ?>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                <?php include('./includes/sidebar.php'); ?>

                <!-- / Menu -->

                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Navbar -->


                    <?php include('./includes/navbar.php'); ?>

                    <!-- / Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->

                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="row">
                                <div class="col-lg-8 mb-4 order-0">
                                    <div class="card">
                                        <div class="d-flex align-items-end row">
                                            <div class="col-sm-7">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">Congratulations
                                                        <?php echo $_SESSION['username']; ?> 🎉
                                                    </h5>
                                                    <p class="mb-4">
                                                        You have done <span class="fw-bold">72%</span> more sales today.
                                                        Check your new badge in
                                                        your profile.
                                                    </p>

                                                    <a href="javascript:;" class="btn btn-sm btn-outline-primary">View
                                                        Badges</a>
                                                </div>
                                            </div>
                                            <div class="col-sm-5 text-center text-sm-left">
                                                <div class="card-body pb-0 px-0 px-md-4">
                                                    <img src="./assets/img/illustrations/man-with-laptop-light.png"
                                                        height="140" alt="View Badge User"
                                                        data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                                        data-app-light-img="illustrations/man-with-laptop-light.png" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 order-1">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div
                                                        class="card-title d-flex align-items-start justify-content-between">
                                                        <div class="avatar flex-shrink-0">
                                                            <img src="./assets/img/icons/unicons/chart-success.png"
                                                                alt="chart success" class="rounded" />
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn p-0" type="button" id="cardOpt3"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end"
                                                                aria-labelledby="cardOpt3">
                                                                <a class="dropdown-item" href="javascript:void(0);">View
                                                                    More</a>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0);">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="fw-semibold d-block mb-1">Total Amount</span>
                                                    <h5 class="card-title mb-2"><?php echo $totalAmount; ?>
                                                    </h5>
                                                    <small class="text-success fw-semibold"><i
                                                            class="bx bx-up-arrow-alt"></i> +72.80%</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div
                                                        class="card-title d-flex align-items-start justify-content-between">
                                                        <div class="avatar flex-shrink-0">
                                                            <img src="./assets/img/icons/unicons/wallet-info.png"
                                                                alt="Credit Card" class="rounded" />
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn p-0" type="button" id="cardOpt6"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end"
                                                                aria-labelledby="cardOpt6">
                                                                <a class="dropdown-item" href="javascript:void(0);">View
                                                                    More</a>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0);">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span>Sales</span>
                                                    <h5 class="card-title text-nowrap mb-1">
                                                        <?php echo $paidAmount; ?>
                                                    </h5>
                                                    <small class="text-success fw-semibold"><i
                                                            class="bx bx-up-arrow-alt"></i> +28.42%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Total Revenue -->
                                <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                                    <div class="card">
                                        <div class="row row-bordered g-0">
                                            <div class="col-md-8">
                                                <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                                                <div id="totalRevenueChart" class="px-2"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                                type="button" id="growthReportId" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                2022
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end"
                                                                aria-labelledby="growthReportId">
                                                                <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">2019</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="growthChart"></div>
                                                <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

                                                <div
                                                    class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                                    <div class="d-flex">
                                                        <div class="me-2">
                                                            <span class="badge bg-label-primary p-2"><i
                                                                    class="bx bx-dollar text-primary"></i></span>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <small>2022</small>
                                                            <h6 class="mb-0">$32.5k</h6>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="me-2">
                                                            <span class="badge bg-label-info p-2"><i
                                                                    class="bx bx-wallet text-info"></i></span>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <small>2021</small>
                                                            <h6 class="mb-0">$41.2k</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Total Revenue -->
                                <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                                    <div class="row">
                                        <div class="col-6 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div
                                                        class="card-title d-flex align-items-start justify-content-between">
                                                        <div class="avatar flex-shrink-0">
                                                            <img src="./assets/img/icons/unicons/paypal.png"
                                                                alt="Credit Card" class="rounded" />
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn p-0" type="button" id="cardOpt4"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end"
                                                                aria-labelledby="cardOpt4">
                                                                <a class="dropdown-item" href="javascript:void(0);">View
                                                                    More</a>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0);">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="d-block mb-1">Payments</span>
                                                    <h5 class="card-title text-nowrap mb-2"> <?php echo $paidAmount; ?>
                                                    </h5>
                                                    <small class="text-danger fw-semibold"><i
                                                            class="bx bx-down-arrow-alt"></i> -14.82%</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div
                                                        class="card-title d-flex align-items-start justify-content-between">
                                                        <div class="avatar flex-shrink-0">
                                                            <img src="./assets/img/icons/unicons/cc-primary.png"
                                                                alt="Credit Card" class="rounded" />
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn p-0" type="button" id="cardOpt1"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                                                <a class="dropdown-item" href="javascript:void(0);">View
                                                                    More</a>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0);">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="fw-semibold d-block mb-1">Transactions</span>
                                                    <h5 class="card-title mb-2"> <?php echo $remaining_amount; ?>
                                                    </h5>
                                                    <small class="text-success fw-semibold"><i
                                                            class="bx bx-up-arrow-alt"></i> +28.14%</small>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- </div>
    <div class="row"> -->
                                        <div class="col-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div
                                                        class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                                        <div
                                                            class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                                            <div class="card-title">
                                                                <h5 class="text-nowrap mb-2">Profile Report</h5>
                                                                <span class="badge bg-label-warning rounded-pill">Year
                                                                    2021</span>
                                                            </div>
                                                            <div class="mt-sm-auto">
                                                                <small class="text-success text-nowrap fw-semibold"><i
                                                                        class="bx bx-chevron-up"></i> 68.2%</small>
                                                                <h3 class="mb-0">$84,686k</h3>
                                                            </div>
                                                        </div>
                                                        <div id="profileReportChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / Content -->

                        <!-- Footer -->
                        <!-- / Footer -->

                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- / Layout wrapper -->
    <?Php } ?>

    <?php include('./includes/footer.php'); ?>
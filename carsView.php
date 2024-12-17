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

if (isset($_POST['edit_customer'])) {
    $c_id = $_POST['c_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $updateQuery = "UPDATE `customers` SET `name`='$name', `email`='$email', `phone`='$phone', `address`='$address', `updated_at`=NOW() WHERE `c_id`=$c_id";
    $conn->query($updateQuery);
}

if (isset($_POST['delete_car'])) {
    $car_id = $_POST['car_id'];

    // Delete query
    $deleteQuery = "DELETE FROM `cars` WHERE `car_id` = $car_id";

    if ($conn->query($deleteQuery) === TRUE) {
        echo "<script>alert('Car deleted successfully'); window.location.href = 'carsView.php';</script>";
    } else {
        echo "<script>alert('Error deleting car'); window.location.href = 'your_page.php';</script>";
    }
}

$query = "SELECT `car_id`, `brand`, `model`, `price`, `registration_number`, `description`, `created_at` FROM `cars`";
$result = $conn->query($query);
?>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include('./includes/sidebar.php'); ?>
            <div class="layout-page">
                <?php include('./includes/navbar.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">Cars</h4>

                        <!-- Display Cars Table -->
                        <div class="card">
                            <h5 class="card-header">Cars List</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>SNo / الرقم التسلسلي</th>
                                            <th>Brand / العلامة التجارية</th>
                                            <th>Model / الموديل</th>
                                            <th>Price / السعر</th>
                                            <th>Registration Number / رقم التسجيل</th>
                                            <th>Description / الوصف</th>
                                            <th>Actions / الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sno = 1;
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$sno}</td>
                                                    <td>{$row['brand']}</td>
                                                    <td>{$row['model']}</td>
                                                    <td>{$row['price']}</td>
                                                    <td>{$row['registration_number']}</td>
                                                    <td>{$row['description']}</td>
                                                    <td>
                                                        <div class='d-flex'>
                                                            <button class='btn btn-sm btn-primary me-2' data-bs-toggle='modal' data-bs-target='#editModal{$row['car_id']}'>Edit</button>
                                                            <!-- Delete Button -->
                                                            <form method='POST' action='carsView.php' onsubmit='return confirm(\"Are you sure you want to delete this car?\");'>
                                                                <input type='hidden' name='car_id' value='{$row['car_id']}'>
                                                                <button type='submit' name='delete_car' class='btn btn-sm btn-danger'>Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>";
                                                $sno++;
                                                ?>
                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal<?php echo $row['car_id']; ?>"
                                                    tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel">Edit Car</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="car_id"
                                                                        value="<?php echo $row['car_id']; ?>">
                                                                    <div class="mb-3">
                                                                        <label for="brand" class="form-label">Brand</label>
                                                                        <input type="text" class="form-control" id="brand"
                                                                            name="brand" value="<?php echo $row['brand']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="model" class="form-label">Model</label>
                                                                        <input type="text" class="form-control" id="model"
                                                                            name="model" value="<?php echo $row['model']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="price" class="form-label">Price</label>
                                                                        <input type="number" class="form-control" id="price"
                                                                            name="price" value="<?php echo $row['price']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="registration_number"
                                                                            class="form-label">Registration Number</label>
                                                                        <input type="text" class="form-control"
                                                                            id="registration_number" name="registration_number"
                                                                            value="<?php echo $row['registration_number']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="description"
                                                                            class="form-label">Description</label>
                                                                        <textarea class="form-control" id="description"
                                                                            name="description" rows="3"
                                                                            required><?php echo $row['description']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" name="edit_car"
                                                                        class="btn btn-primary">Save changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>No records found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">©
                                <script>document.write(new Date().getFullYear());</script>
                            </div>
                        </div>
                    </footer>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script>
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
    <?php include('./includes/footer.php'); ?>
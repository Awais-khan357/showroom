<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include('./includes/header.php');
include('./includes/dbConfig.php');

// Edit functionality for customer
if (isset($_POST['edit_customer'])) {
    $c_id = $_POST['c_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update query for editing customer
    $updateQuery = "UPDATE `customers` SET 
                    `name`='$name', 
                    `email`='$email', 
                    `phone`='$phone', 
                    `address`='$address', 
                    `updated_at`=NOW() 
                    WHERE `c_id`=$c_id";
    if ($conn->query($updateQuery) === TRUE) {
        echo "<script>alert('Customer updated successfully'); window.location.href = 'customerView.php';</script>";
    } else {
        echo "<script>alert('Error updating customer'); window.location.href = 'customerView.php';</script>";
    }
}

// Delete functionality for customer
if (isset($_POST['delete_customer'])) {
    $c_id = $_POST['c_id'];

    // Delete query for customer
    $deleteQuery = "DELETE FROM `customers` WHERE `c_id` = $c_id";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "<script>alert('Customer deleted successfully'); window.location.href = 'customerView.php';</script>";
    } else {
        echo "<script>alert('Error deleting customer'); window.location.href = 'customerView.php';</script>";
    }
}

// Fetch customer details
$query = "SELECT `c_id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at` FROM `customers`";
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
                        <h4 class="fw-bold py-3 mb-4">Customers</h4>

                        <!-- Display Customers Table -->
                        <div class="card">
                            <h5 class="card-header">Customer List</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>SNo / الرقم التسلسلي</th>
                                            <th>Name / الاسم</th>
                                            <th>Email / البريد الإلكتروني</th>
                                            <th>Phone / الهاتف</th>
                                            <th>Address / العنوان</th>
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
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['email']}</td>
                                                    <td>{$row['phone']}</td>
                                                    <td>{$row['address']}</td>
                                                    <td>
                                                        <div class='d-flex'>
                                                            <button class='btn btn-sm btn-primary me-2' data-bs-toggle='modal' data-bs-target='#editModal{$row['c_id']}'>Edit</button>
                                                            <!-- Delete Button -->
                                                            <form method='POST' action='customerView.php' onsubmit='return confirm(\"Are you sure you want to delete this customer?\");'>
                                                                <input type='hidden' name='c_id' value='{$row['c_id']}'>
                                                                <button type='submit' name='delete_customer' class='btn btn-sm btn-danger'>Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>";
                                                $sno++;
                                                ?>
                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal<?php echo $row['c_id']; ?>" tabindex="-1"
                                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel">Edit Customer
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="c_id"
                                                                        value="<?php echo $row['c_id']; ?>">
                                                                    <div class="mb-3">
                                                                        <label for="name" class="form-label">Name</label>
                                                                        <input type="text" class="form-control" id="name"
                                                                            name="name" value="<?php echo $row['name']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="email" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" id="email"
                                                                            name="email" value="<?php echo $row['email']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="phone" class="form-label">Phone</label>
                                                                        <input type="text" class="form-control" id="phone"
                                                                            name="phone" value="<?php echo $row['phone']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="address" class="form-label">Address</label>
                                                                        <textarea class="form-control" id="address"
                                                                            name="address" rows="3"
                                                                            required><?php echo $row['address']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" name="edit_customer"
                                                                        class="btn btn-primary">Save changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No records found</td></tr>";
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


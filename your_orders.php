<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
} else {
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>My Orders</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css" rel="stylesheet">
        /* Custom styles for tables */
        /* Responsive table design */
    </style>
</head>

<body>
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"><img class="img-rounded" src="images/logo.png" alt="" width="18%"></a>
                <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="restaurants.php">Restaurants</a></li>
                        <?php
                        if (empty($_SESSION["user_id"])) {
                            echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a></li>
                                  <li class="nav-item"><a href="registration.php" class="nav-link active">Register</a></li>';
                        } else {
                            echo '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a></li>';
                            echo '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-wrapper">
        <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
            <div class="container"></div>
        </div>
        <div class="result-show">
            <div class="container">
                <div class="row"></div>
            </div>
        </div>

        <section class="restaurants-page">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="bg-gray">
                            <div class="row">
                                <table class="table table-bordered table-hover">
                                    <thead style="background: #404040; color:white;">
                                        <tr>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            // Call the GetUserOrders stored procedure
                                            $userId = $_SESSION['user_id'];
                                            $query_res = mysqli_query($db, "CALL GetUserOrders('$userId')");

                                            if (!$query_res || mysqli_num_rows($query_res) == 0) {
                                                echo '<td colspan="6"><center>You have no orders placed yet.</center></td>';
                                            } else {
                                                while ($row = mysqli_fetch_array($query_res)) {
                                        ?>
                                        <tr>
                                            <td data-column="Item"><?php echo $row['title']; ?></td>
                                            <td data-column="Quantity"><?php echo $row['quantity']; ?></td>
                                            <td data-column="price">$<?php echo $row['price']; ?></td>
                                            <td data-column="status">
                                                <?php
                                                    $status = $row['status'];
                                                    if ($status == "" || $status == "NULL") {
                                                        echo '<button type="button" class="btn btn-info"><span class="fa fa-bars" aria-hidden="true"></span> Dispatch</button>';
                                                    } elseif ($status == "in process") {
                                                        echo '<button type="button" class="btn btn-warning"><span class="fa fa-cog fa-spin" aria-hidden="true"></span> On The Way!</button>';
                                                    } elseif ($status == "closed") {
                                                        echo '<button type="button" class="btn btn-success"><span class="fa fa-check-circle" aria-hidden="true"></span> Delivered</button>';
                                                    } elseif ($status == "rejected") {
                                                        echo '<button type="button" class="btn btn-danger"> <i class="fa fa-close"></i> Cancelled</button>';
                                                    }
                                                ?>
                                            </td>
                                            <td data-column="Date"><?php echo $row['date']; ?></td>
                                            <td data-column="Action">
                                                <a href="delete_orders.php?order_del=<?php echo $row['o_id']; ?>" onclick="return confirm('Are you sure you want to cancel your order?');" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                    <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                                }
                                            } 
                                            mysqli_free_result($query_res);  // Free up memory
                                            mysqli_next_result($db);  // Prepare for the next query
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include "include/footer.php" ?>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>

</html>
<?php
}
?>

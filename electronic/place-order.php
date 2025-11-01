<?php
session_start();
require_once 'config/database.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    try {
        $user_id = $_SESSION['user_id'] ?? null;
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $amount = (float)$_POST['amount'];
        
        $query = "INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_amount) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        
        if($stmt->execute([$user_id, $name, $email, $phone, $address, $amount])) {
            $order_id = $db->lastInsertId();
            echo "<script>window.location.href='my-orders.php';</script>";
            exit();
        } else {
            $error = 'Failed to place order. Please try again.';
        }
    } catch(Exception $e) {
        $error = 'Error placing order: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - Mahadev Electronic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-shopping-cart me-2"></i>Place Order</h4>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo $_SESSION['user_name'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $_SESSION['user_email'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount (₹)</label>
                                <input type="number" class="form-control" name="amount" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Place Order
                            </button>
                            <a href="products.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Products
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
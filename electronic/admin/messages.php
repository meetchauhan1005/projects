<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Handle message status update
if($_POST && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $query = "UPDATE contact_messages SET status = :status WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':status', $_POST['status']);
    $stmt->bindParam(':id', $_POST['message_id']);
    $stmt->execute();
}

// Get messages
$query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%); min-height: 100vh; position: fixed; width: 280px; }
        .main-content { margin-left: 280px; padding: 20px; }
        .nav-link { color: #bdc3c7; padding: 15px 25px; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(52, 152, 219, 0.2); color: #fff; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .btn-primary { background: linear-gradient(45deg, #3498db, #2980b9); border: none; border-radius: 25px; }
        .message-card { transition: transform 0.3s ease; }
        .message-card:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-4 text-white border-bottom">
            <div class="d-flex align-items-center mb-2">
                <img src="../assets/images/logo.svg" alt="Mahadev Electronic" height="35" class="me-2">
                <h5 class="mb-0">Mahadev Electronic</h5>
            </div>
            <small class="text-light">Admin Dashboard</small>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>
            <a class="nav-link" href="products.php">
                <i class="fas fa-box me-3"></i> Products
            </a>
            <a class="nav-link" href="categories.php">
                <i class="fas fa-tags me-3"></i> Categories
            </a>
            <a class="nav-link active" href="messages.php">
                <i class="fas fa-envelope me-3"></i> Messages
            </a>
            <hr class="text-light mx-3">
            <a class="nav-link" href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt me-3"></i> View Website
            </a>
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt me-3"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Contact Messages</h1>
                <p class="text-muted">Manage customer inquiries and support requests</p>
            </div>
        </div>

        <!-- Messages -->
        <div class="row">
            <?php foreach($messages as $message): ?>
            <div class="col-12 mb-4">
                <div class="card message-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($message['name']); ?></h6>
                                <div class="text-muted small">
                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($message['email']); ?>
                                    <?php if($message['phone']): ?>
                                    <span class="ms-3"><i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($message['phone']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-<?php 
                                echo $message['status'] == 'new' ? 'primary' : 
                                    ($message['status'] == 'read' ? 'warning' : 'success'); 
                            ?> mb-2">
                                <i class="fas fa-<?php 
                                    echo $message['status'] == 'new' ? 'circle' : 
                                        ($message['status'] == 'read' ? 'eye' : 'check'); 
                                ?> me-1"></i><?php echo ucfirst($message['status']); ?>
                            </span>
                            <div class="text-muted small">
                                <i class="fas fa-clock me-1"></i><?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="bg-light rounded p-3 mb-4">
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <?php if($message['status'] == 'new'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                <input type="hidden" name="status" value="read">
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fas fa-eye me-1"></i> Mark as Read
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <?php if($message['status'] != 'replied'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                <input type="hidden" name="status" value="replied">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-reply me-1"></i> Mark as Replied
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <a href="mailto:<?php echo $message['email']; ?>?subject=Re: Your inquiry&body=Dear <?php echo htmlspecialchars($message['name']); ?>,%0D%0A%0D%0AThank you for contacting Mahadev Electronic.%0D%0A%0D%0A" class="btn btn-primary btn-sm">
                                <i class="fas fa-envelope me-1"></i> Reply via Email
                            </a>
                            
                            <?php if($message['phone']): ?>
                            <a href="tel:<?php echo $message['phone']; ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-phone me-1"></i> Call Customer
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(count($messages) == 0): ?>
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4>No Messages Found</h4>
                        <p class="text-muted">Customer messages will appear here when they contact you</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

// Check if BookingID is passed in the URL
if (isset($_GET['BookingID']) && is_numeric($_GET['BookingID'])) {
    $bookingID = $_GET['BookingID'];

    // Fetch payment details based on BookingID
    try {
        $stmt = $conn->prepare("SELECT TotalCost FROM Bookings WHERE BookingID = :booking_id");
        $stmt->execute(['booking_id' => $bookingID]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a valid booking is found
        if ($booking) {
            $paymentAmount = $booking['TotalCost'];
        } else {
            die("Invalid Booking ID.");
        }
    } catch (PDOException $e) {
        die("Error fetching booking details: " . $e->getMessage());
    }
} else {
    die("Booking ID not provided or invalid.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['paymentMethod'];

    // Insert payment record into the Payments table
    try {
        $stmt = $conn->prepare("INSERT INTO Payments (BookingID, PaymentAmount, PaymentMethod) 
                                VALUES (:booking_id, :payment_amount, :payment_method)");
        $stmt->execute([
            'booking_id' => $bookingID,
            'payment_amount' => $paymentAmount,
            'payment_method' => $paymentMethod
        ]);
        
        // Store a flag to trigger the popup in JavaScript
        echo "<script>
                alert('Payment Successful, Thank you for booking with Travel Manager');
                window.location.href = 'user_home.php';
              </script>";
        exit;
    } catch (PDOException $e) {
        die("Error inserting payment: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Manager - Payment</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #1f1f1f; /* Dark Gray */
    background-image: url('../images/hero-background.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    margin: 0;
    padding: 0;
    color: #d4d4d4; /* Light Gray */
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Ensure body covers full viewport height */
}

header {
    background-color: #a80000; /* Bright Red */
    color: white;
    padding: 10px 20px;
    text-align: center;
}

nav {
    background-color: #2d2d2d; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
    padding: 10px 20px;
    text-align: center;
}

nav .navbar-menu {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 10px 0;
}

nav .navbar-menu a {
    color: #d4d4d4; /* Light Gray */
    text-decoration: none;
    font-size: 1rem;
}

nav .navbar-menu a:hover {
    color: #a80000; /* Bright Red */
}

.container {
    width: 90%;
    max-width: 600px;
    margin: 20px auto;
    background: rgba(23, 23, 23, 0.8); /* Sheer Dark Gray */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); /* Bolder shadow */
    flex: 1; /* Pushes the footer down when content is insufficient */
}

.btn-primary {
    padding: 10px 15px;
    background-color: #a80000; /* Bright Red */
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
    display: inline-block;
}

.btn-primary:hover {
    background-color: #731010; /* Dark Red */
}

footer p {
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
    padding: 10px 20px;
    text-align: center;
    margin: 0;
    font-size: 0.9rem;
}
    </style>
    <script>
        function showPopup(event) {
            event.preventDefault();
            alert("Payment Successful, Thank you for booking with Travel Manager");
            document.getElementById("paymentForm").submit();
        }
    </script>
</head>
<body>
    <header>
        <h1>Travel Manager</h1>
    </header>
    <nav>
        <div class="navbar-menu">
            <a href="user_home.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>Payment for Your Booking</h2>
        <p><strong>Booking ID:</strong> <?= htmlspecialchars($bookingID) ?></p>
        <p><strong>Total Payment Amount:</strong> ₹<?= htmlspecialchars($paymentAmount) ?></p>

        <form method="POST" id="paymentForm">
            <label for="paymentMethod">Choose Payment Method:</label>
            <select name="paymentMethod" id="paymentMethod" required>
                <option value="Credit Card">Credit Card</option>
                <option value="UPI">UPI</option>
                <option value="Net Banking">Net Banking</option>
            </select>
            <br><br>
            <button type="button" class="btn-primary" onclick="showPopup(event)">Submit Payment</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Travel Manager. All Rights Reserved.</p>
    </footer>
</body>
</html>



<!-- final book.php -->
<?php
include 'database.php';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['user_name'];
    $destinationID = $_POST['destination_id'];
    $numberOfPeople = $_POST['number_of_people'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $transportID = $_POST['transport_id'];

    try {
        // Fetch UserID based on UserName
        $userQuery = $conn->prepare("SELECT UserID FROM Users WHERE UserName = :userName");
        $userQuery->execute([':userName' => $userName]);
        $user = $userQuery->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("User not found.");
        }
        $userID = $user['UserID'];

        // Fetch destination details
        $destinationQuery = $conn->prepare("SELECT PricePerPersonPerDay FROM Destinations WHERE DestinationID = :destinationID");
        $destinationQuery->execute([':destinationID' => $destinationID]);
        $destination = $destinationQuery->fetch(PDO::FETCH_ASSOC);

        if (!$destination) {
            throw new Exception("Destination not found.");
        }

        $pricePerPersonPerDay = $destination['PricePerPersonPerDay'];

        // Calculate the total cost
        $numberOfDays = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);  // Number of days
        $totalCost = $pricePerPersonPerDay * $numberOfPeople * $numberOfDays;

        // Fetch transport details
        $transportQuery = $conn->prepare("SELECT Cost FROM Transportation WHERE TransportID = :transportID");
        $transportQuery->execute([':transportID' => $transportID]);
        $transport = $transportQuery->fetch(PDO::FETCH_ASSOC);

        if (!$transport) {
            throw new Exception("Transport mode not found.");
        }

        $transportCost = $transport['Cost'];

        // Add transport cost to total cost (this is a fixed cost for the entire trip)
        $totalCost += $transportCost;

        // Insert booking details into the database
        $query = $conn->prepare("
            INSERT INTO Bookings (UserID, DestinationID, NumberOfPeople, TotalCost, TripStartDate, TripEndDate, TransportID)
            VALUES (:userID, :destinationID, :numberOfPeople, :totalCost, :startDate, :endDate, :transportID)
        ");
        $query->execute([
            ':userID' => $userID,
            ':destinationID' => $destinationID,
            ':numberOfPeople' => $numberOfPeople,
            ':totalCost' => $totalCost,
            ':startDate' => $startDate,
            ':endDate' => $endDate,
            ':transportID' => $transportID,
        ]);

        // Get the BookingID of the newly inserted booking
        $bookingID = $conn->lastInsertId();

        // Redirect to the payment page with the BookingID
        header("Location: payments.php?BookingID=" . $bookingID);
        exit;
    } catch (Exception $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    // Fetch destinations
    $destinations = $conn->query("SELECT DestinationID, LocationName, PricePerPersonPerDay FROM Destinations")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Trip</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
}

.header {
    background-color: #a80000; /* Bright Red */
    color: white;
    text-align: center;
    padding: 10px 0;
}

.navbar {
    display: flex;
    justify-content: center;
    background-color: #1f1f1f; /* Dark Gray */
    padding: 10px 0;
}

.navbar a {
    color: #d4d4d4; /* Light Gray */
    text-decoration: none;
    margin: 0 15px;
    font-weight: bold;
}

.navbar a:hover {
    color: #a80000; /* Bright Red */
    text-decoration: underline;
}

.container {
    background-color: rgba(23, 23, 23, 0.8); /* Sheer Dark Gray */
    margin: 20px auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); /* Enhanced shadow for boldness */
    width: 70%;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
    color: #d4d4d4; /* Light Gray */
}

input, select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #707070; /* Medium Gray */
    border-radius: 4px;
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
}

button {
    margin-top: 20px;
    background-color: #a80000; /* Bright Red */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #731010; /* Dark Red */
}

.footer {
    text-align: center;
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
    padding: 10px 0;
    position: relative;
    bottom: 0;
    width: 100%;
}

.transport-section {
    margin-top: 15px;
}

    </style>
    <script>
        async function updateTransportation(destinationID) {
            // Fetch transport modes for the selected destination
            const transportSection = document.getElementById('transport_section');
            const transportSelect = document.getElementById('transport_id');
            const numberOfPeople = document.getElementById('number_of_people').value || 0;

            if (destinationID) {
                const response = await fetch(get_transport.php?destination_id=${destinationID});
                const transports = await response.json();

                transportSelect.innerHTML = '<option value="" disabled selected>Choose transport mode</option>';
                transports.forEach(transport => {
                    transportSelect.innerHTML += <option value="${transport.TransportID}" data-price="${transport.Cost}">${transport.TransportMode} - ₹${transport.Cost}</option>;
                });

                transportSection.style.display = 'block';
            } else {
                transportSection.style.display = 'none';
            }

            updateCost();
        }

        function updateCost() {
            const destination = document.getElementById('destination_id');
            const pricePerPersonPerDay = destination.selectedOptions[0]?.getAttribute('data-price') || 0;
            const numberOfPeople = document.getElementById('number_of_people').value || 0;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            const transport = document.getElementById('transport_id');
            const transportCost = transport.selectedOptions[0]?.getAttribute('data-price') || 0;

            // Calculate the total cost (including transport)
            const numberOfDays = (new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24);  // Number of days
            const totalCost = (pricePerPersonPerDay * numberOfPeople * numberOfDays) + parseFloat(transportCost);

            // Display the number of days
            document.getElementById('total_days').innerText = Total Days: ${numberOfDays};

            // Update the displayed total cost
            document.getElementById('total_cost').innerText = Total Cost: ₹${totalCost.toFixed(2)};

            // Set the hidden input field to the calculated total cost
            document.getElementById('total_cost_input').value = totalCost.toFixed(2);
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>Travel Manager</h1>
    </div>

    <div class="navbar">
        <a href="user_home.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h1>Book a Trip</h1>
        <form action="book.php" method="POST">
            <label for="user_name">User Name:</label>
            <input type="text" id="user_name" name="user_name" required>

            <label for="destination_id">Select Destination:</label>
            <select id="destination_id" name="destination_id" onchange="updateTransportation(this.value)" required>
                <option value="" disabled selected>Choose a destination</option>
                <?php foreach ($destinations as $destination): ?>
                    <option value="<?= $destination['DestinationID'] ?>" data-price="<?= $destination['PricePerPersonPerDay'] ?>">
                        <?= htmlspecialchars($destination['LocationName']) ?> - ₹<?= htmlspecialchars($destination['PricePerPersonPerDay']) ?>/person
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="number_of_people">Number of People:</label>
            <input type="number" id="number_of_people" name="number_of_people" value="1" min="1" onchange="updateCost()" required>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" onchange="updateCost()" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" onchange="updateCost()" required>

            <label for="transport_id">Transport Mode:</label>
            <select id="transport_id" name="transport_id" onchange="updateCost()" required>
                <option value="" disabled selected>Choose transport mode</option>
            </select>
            
            <p id="total_days">Total Days: 0</p>
            <p id="total_cost">Total Cost: ₹0.00</p>
            <input type="hidden" id="total_cost_input" name="total_cost">

            <button type="submit">Proceed to Payment</button>
        </form>
    </div>

    <div class="footer">
        © 2024 Travel Manager. All Rights Reserved.
    </div>
</body>
</html>
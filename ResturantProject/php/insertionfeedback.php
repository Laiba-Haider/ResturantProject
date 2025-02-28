<?php
// Include database connection file
include '../dbc/db_connect.php';

// Display the navigation bar
echo '
<nav>
<h2>Pakistani Zaika</h2>
<a href="../html/Homepage.html">Home</a>
<a href="../html/About.html">About Us</a>
<a href="../html/Booktable.html">Book Table</a>
<a href="Menu1.php">Menu</a>
<a href="../html/Login.html">Login</a>
<a href="../html/CreateAccount.html">Sign up</a>
<a href="../html/Feedback.html">Feedback</a>
</nav>';

// CSS Styling for the nav bar and feedback display
echo '
<style>
body{
    margin:0;
    background-color:lightcyan;
}
nav {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 20px 0;
}
nav h2 {
    color: white;
    font-family: Blackadder ITC;
    font-size: 35px;
    margin-right: auto;
    padding-left: 8%;
}
nav a {
    color: white;
    margin-right: 20px;
    text-decoration: none;
    padding: 0 15px;
    transition: color 0.3s ease;
}
nav a:hover {
    color: #FFB6C1;
    font-weight: bold;
    text-decoration-line: underline;
}
.feedback-table {
    margin: 20px auto;
    border-collapse: collapse;
    width: 80%;
}
.feedback-table th, .feedback-table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}
.feedback-table th {
    background-color: #f2f2f2;
}
</style>
';

// Check if the request is valid
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data using $_REQUEST
    $feedbackRatings = isset($_REQUEST['feedbackRatings']) ? intval($_REQUEST['feedbackRatings']) : null;
    $feedbackDate = isset($_REQUEST['feedbackDate']) ? $_REQUEST['feedbackDate'] : null;
    $feedbackReviews = isset($_REQUEST['feedbackReviews']) ? $_REQUEST['feedbackReviews'] : null;
    $customerName = isset($_REQUEST['customerName']) ? $_REQUEST['customerName'] : null;

    // Validate required fields
    if ($feedbackRatings && $feedbackDate && $customerName) {
        // Fetch the user ID from the users table based on the customer name
        $userQuery = "SELECT id FROM users WHERE username = ?";
        if ($userStmt = $conn->prepare($userQuery)) {
            $userStmt->bind_param('s', $customerName);
            $userStmt->execute();
            $userStmt->bind_result($userId);
            $userStmt->fetch();
            $userStmt->close();

            if ($userId) {
                // Prepare the SQL statement for insertion
                $sql = "INSERT INTO Feedbacks (FeedbackRatings, FeedbackDate, FeedbackReviews, CustomerId) 
                VALUES (?, ?, ?, ?)";

                // Prepare and bind parameters
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param('issi', $feedbackRatings, $feedbackDate, $feedbackReviews, $userId);

                    // Execute the statement
                    if ($stmt->execute()) {
                        // echo "Feedback submitted successfully!";

                        // Fetch and display the submitted feedback
                        $feedbackQuery = "SELECT FeedbackRatings, FeedbackDate, FeedbackReviews FROM Feedbacks 
                        WHERE CustomerId = ? AND FeedbackDate = ? AND FeedbackReviews = ?
                        ORDER BY FeedbackId DESC LIMIT 1";
                        if ($feedbackStmt = $conn->prepare($feedbackQuery)) {
                            $feedbackStmt->bind_param('iss', $userId, $feedbackDate, $feedbackReviews);
                            $feedbackStmt->execute();
                            $feedbackResult = $feedbackStmt->get_result();

                            if ($feedbackResult->num_rows > 0) {
                                echo "<table class='feedback-table'>
                                <tr>
                                <th>Ratings</th>
                                <th>Date</th>
                                <th>Reviews</th>
                                </tr>";

                                while ($row = $feedbackResult->fetch_assoc()) {
                                    echo "<tr>
                                    <td>" . htmlspecialchars($row['FeedbackRatings']) . "</td>
                                    <td>" . htmlspecialchars($row['FeedbackDate']) . "</td>
                                    <td>" . htmlspecialchars($row['FeedbackReviews']) . "</td>
                                    </tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "Error retrieving feedback details.";
                            }
                            $feedbackStmt->close();
                        } else {
                            echo "Error preparing feedback retrieval statement: " . $conn->error;
                        }
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }
            } else {
                echo "Customer name not found in users.";
            }
        } else {
            echo "Error fetching user ID: " . $conn->error;
        }
    } else {
        echo "Please fill in all required fields.";
    }

    // Close the connection
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

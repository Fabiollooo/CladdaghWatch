<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #2c3e50;
        }
        .details {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #6b7280;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Patrol Postponed</h1>

        <p>Hello <?php echo htmlspecialchars($volunteerName); ?>,</p>

        <p>Your scheduled patrol has been postponed to a new date.</p>

        <div class="details">
            <h3>Updated Patrol Details:</h3>
            <p><strong>Patrol Type:</strong> <?php echo htmlspecialchars($patrolDescription ?? 'Regular Patrol'); ?></p>
            <p><strong>Original Date:</strong> <?php echo htmlspecialchars($oldPatrolDate); ?></p>
            <p><strong>New Date:</strong> <?php echo htmlspecialchars($newPatrolDate); ?></p>
        </div>

        <p><strong>Please volunteer again for the new date</strong> if you are still available.</p>

        <p>Please contact the manager for further information.</p>

        <div class="footer">
            <p>Claddagh Watch Volunteer Program</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
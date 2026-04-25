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
            border-left: 4px solid #3498db;
            margin: 20px 0;
        }
        .details p {
            margin: 8px 0;
        }
        .custom-message {
            background-color: #fff9e6;
            padding: 15px;
            border-left: 4px solid #fbbf24;
            margin: 20px 0;
            border-radius: 3px;
        }
        .custom-message p {
            margin: 8px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 15px;
            font-weight: bold;
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
        <h1>We Need Your Help!</h1>
        
        <p>Dear Volunteer,</p>
        
        <p>Claddagh Watch is seeking additional volunteers for an upcoming patrol. We would greatly appreciate your support in keeping our community safe.</p>
        
        <div class="details">
            <h3>Patrol Information:</h3>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($patrolDate); ?></p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($patrolDescription); ?></p>
            <p><strong>Need Volunteers:</strong> <?php echo htmlspecialchars($needVolunteers); ?></p>
        </div>
        
        <?php if (!empty($customMessage)): ?>
        <div class="custom-message">
            <h3 style="margin-top: 0;">Additional Information:</h3>
            <p><?php echo nl2br(htmlspecialchars($customMessage)); ?></p>
        </div>
        <?php endif; ?>
        
        <p>If you are available and interested in joining this patrol, please log in to your volunteer account to confirm your participation.</p>
        
        <p>Thank you for your dedication to Claddagh Watch and our community!</p>
        
        <div class="footer">
            <p>Claddagh Watch Volunteer Program</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

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
        <h1>Thank You for Volunteering!</h1>
        
        <p>Hello <?php echo $volunteerName; ?>,</p>
        
        <p>We're excited to confirm that you've successfully signed up to volunteer with Claddagh Watch!</p>
        
        <div class="details">
            <h3>Patrol Details:</h3>
            <p><strong>Date & Time:</strong> <?php echo $patrolDate; ?></p>
            <p><strong>Patrol Type:</strong> <?php echo $patrolDescription; ?></p>
        </div>
        
        <p>Your commitment to keeping our community safe is greatly appreciated. If you need to make any changes to your volunteer schedule, please contact us as soon as possible.</p>
        
        <p>See you soon!</p>
        
        <div class="footer">
            <p>Claddagh Watch Volunteer Program</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

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
        <h1>Welcome to Claddagh Watch!</h1>
        
        <p>Hello <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>,</p>
        
        <p>Your Claddagh Watch account has been successfully created. Below you'll find your login credentials and account information.</p>
        
        <div class="details">
            <h3>Account Information:</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Password:</strong> <span style="font-family: 'Courier New', monospace; background-color: #f5f5f5; padding: 2px 6px; border-radius: 3px;"><?php echo htmlspecialchars($password); ?></span></p>
            <p><strong>Account Type:</strong> <?php echo htmlspecialchars($userType); ?></p>
        </div>
        
        <p>To get started, please use the credentials above to log in to your account. If you have any questions or need assistance, please don't hesitate to contact the Claddagh Watch administration team.</p>
        
        <p>Thank you for joining Claddagh Watch!</p>
        
        <div class="footer">
            <p>Claddagh Watch</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

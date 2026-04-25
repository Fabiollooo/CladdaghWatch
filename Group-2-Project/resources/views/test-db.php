<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Connection Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Database Connection Test</h1>
        
        <div class="alert <?php echo $dbConnected ? 'alert-success' : 'alert-danger'; ?>" role="alert">
            <?php echo $dbMessage; ?>
        </div>
        
        <?php if ($dbConnected): ?>
            <h3>Schedule Records Found: <?php echo $scheduleCount; ?></h3>
            
            <?php if ($scheduleCount > 0): ?>
                <div class="table-responsive mt-4">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Patrol Nr</th>
                                <th>Patrol Date</th>
                                <th>Description</th>
                                <th>Super User Nr</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><?php echo $schedule->patrolNr; ?></td>
                                    <td><?php echo $schedule->patrolDate; ?></td>
                                    <td><?php echo $schedule->patrolDescription; ?></td>
                                    <td><?php echo $schedule->SuperUserNr ?? 'N/A'; ?></td>
                                    <td><?php echo $schedule->patrol_status; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="alert alert-warning">No schedule records found in the database.</p>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="mt-4">
            <a href="<?php echo url('/schedules'); ?>" class="btn btn-primary">Go to Schedules Page</a>
            <a href="<?php echo url('/home'); ?>" class="btn btn-secondary">Go to Home</a>
        </div>
        
        <div class="mt-5">
            <h4>Database Configuration:</h4>
            <ul>
                <li><strong>Connected Database:</strong> <?php echo $dbName ?? 'Unknown'; ?></li>
                <li><strong>Database:</strong> <?php echo env('DB_DATABASE'); ?></li>
                <li><strong>Host:</strong> <?php echo env('DB_HOST'); ?></li>
                <li><strong>Port:</strong> <?php echo env('DB_PORT'); ?></li>
                <li><strong>Username:</strong> <?php echo env('DB_USERNAME'); ?></li>
            </ul>
        </div>
    </div>
</body>
</html>

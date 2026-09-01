<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Received</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="confirmation-page">
    <div class="confirmation-container">
        <div class="confirmation-card">
            <div class="status-icon">
                <i class="hgi-stroke hgi-checkmark-circle-02 text-3xl text-[var(--success)]"></i>
            </div>



            <div class="message-details">
                <div class="confirmation-header">
                    <h1>Message Received</h1>
                    <p>{{ $name }} We've gotten your message and will be in touch soon</p>

                </div>
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <div class="detail-value">{{ $name }}</div>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email Address</span>
                    <div class="detail-value">{{ $email }}</div>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Your Message</span>
                    <div class="detail-value">{{ $message }}</div>
                </div>
            </div>

            <div class="action-section">
                <a href="/formcreate" class="btn btn-primary piiston-btn-primary">Send Another Message</a>
                <a href="/" class="btn btn-secondary piiston-btn-secondary">Back to Home</a>
            </div>

        </div>
    </div>

</body>
</html>

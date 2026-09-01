<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get in Touch</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="form-page">
    <div class="form-container">
        <div class="form-wrapper">
            <div class="form-header">
                <h1>Contact Us</h1>
                <p>We'd love to hear from you. Please fill out the form below and we'll get back to you as soon as possible.</p>
            </div>

            <form action="/formshow" method="post">
                @csrf
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        required
                        autocomplete="name"
                        placeholder="Full name"
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        autocomplete="email"
                        placeholder="you@example.com"
                    >
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea 
                        id="message" 
                        name="message" 
                        required
                        placeholder="Your message here..."
                    ></textarea>
                </div>

                <button type="submit" class="btn btn-primary submit-btn">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>
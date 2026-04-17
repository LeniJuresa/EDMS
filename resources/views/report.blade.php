<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Anonymous Report</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header-content h1 {
            margin: 0;
            font-size: 2em;
        }

        .emergency-number {
            font-size: 1.2em;
            margin-top: 5px;
        }

        .user-name {
            font-size: 1em;
            margin-top: 10px;
            font-style: italic;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
            gap: 30px;
        }

        .warning-box {
            flex: 1;
            min-width: 300px;
            background-color: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            border-left: 6px solid #ff9900;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        .warning-box h2 {
            margin-top: 0;
            color: #cc5200;
            font-size: 1.4em;
        }

        .warning-box p {
            margin-top: 10px;
            font-size: 1em;
            color: #333;
            line-height: 1.5;
        }

        .report-form {
            flex: 2;
            min-width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        .report-form h2 {
            margin-bottom: 15px;
            color: #003366;
        }

        .report-form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        .report-form input[type="text"],
        .report-form input[type="file"],
        .report-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .report-form button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #ff6600;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .report-form button:hover {
            background-color: #cc5200;
        }
    </style>
</head>
<body>

<header class="header-content">
    <h1>Anonymous Report</h1>
    <div class="emergency-number">Submit a report below</div>
</header>

<div class="container">

    <div class="warning-box">
        <h2>Notice</h2>
        <p>
            This is an emergency reporting system intended for real and serious incidents only.
Abusing this system, submitting false reports, or using it for non-emergency purposes may interfere with emergency services and is not acceptable.

Please use this system responsibly.
        </p>
    </div>

    <div class="report-form">
        <h2>Submit New Report</h2>
        <form id="reportForm" enctype="multipart/form-data" action="/report" method="POST">

            @csrf
            <label for="location">Location *</label>
            <input type="text" name="location" placeholder="Location" required>

            <label for="description">Description *</label>
            <textarea name="description" placeholder="Description" required></textarea>

            <label for="image">Image (optional)</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">Submit</button>
        </form>
    </div>

</div>


</body>
</html>

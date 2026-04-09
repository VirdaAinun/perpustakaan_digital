<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Icon -->
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: #f4f6f9;
        color: #333;
    }

    /* NAVBAR */
    .navbar-wrapper {
        position: fixed;
        top: 0;
        left: 220px;
        right: 0;
        height: 70px;
        background: #ffffff;
        border-bottom: 1px solid #ddd;
        display: flex;
        align-items: center;
        padding: 0 20px;
        z-index: 1000;
    }

    /* SIDEBAR */
    .sidebar-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 220px;
        height: 100vh;
        background: #2c3e50;
        color: white;
        z-index: 1000;
    }

    /* CONTENT */
    .content {
        margin-left: 220px;
        margin-top: 70px;
        min-height: 100vh;
    }

    /* CARD */
    .card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #ddd;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    }

    /* BUTTON */
    .btn {
        background: #34495e;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        color: white;
        cursor: pointer;
        transition: 0.3s;
        font-size: 14px;
    }

    .btn:hover {
        background: #2c3e50;
    }

    /* SCROLLBAR */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

</style>

</head>
<body>
<!-- SIDEBAR -->
<div class="sidebar-wrapper">
    @include('layouts.backend.admin.sidebar')
</div>

<!-- NAVBAR -->
<div class="navbar-wrapper">
    @include('layouts.backend.admin.navbar')
</div>

<!-- CONTENT -->
<div class="content">
    @yield('content')
</div>


</body>
</html>

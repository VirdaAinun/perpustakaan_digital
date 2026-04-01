<style>
.navbar {
    height: 70px;
    width: 100%;
    background: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 30px;
    position: fixed;
    top: 0;
    left: 0;
    border-bottom: 1px solid #ddd;
    z-index: 1000;
}

/* LEFT */
.navbar-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo {
    font-size: 20px;
}

.title {
    font-size: 17px;
    font-weight: 400;
    color: #2c3e50;
    letter-spacing: 0.3px;
    margin-top: 15px; /* ini bikin turun sedikit */
}

/* RIGHT */
.navbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

/* USER */
.user {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    cursor: pointer;
}

.avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #34495e;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    color: white;
}

/* NOTIFICATION ICON */
.icon-btn {
    position: relative;
    cursor: pointer;
    font-size: 18px;
    color: #2c3e50;
}

.icon-btn span {
    position: absolute;
    top: -5px;
    right: -8px;
    background: #e74c3c;
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 50%;
}

/* HOVER */
.icon-btn:hover,
.user:hover {
    opacity: 0.7;
    transition: 0.2s;
}
</style>

<div class="navbar">

<!-- LEFT -->
<div class="navbar-left">
    <div class="logo">📚</div>
    <div class="title">Sistem Informasi Perpustakaan</div>
</div>

<!-- RIGHT -->
<div class="navbar-right">

    <!-- NOTIF -->
    <div class="icon-btn">
        🔔
        <span>3</span>
    </div>

    <!-- USER -->
    <div class="user">
        <div class="avatar">A</div>
        <div>
            <div style="font-weight:500;">Administrator</div>
            <div style="font-size:12px; color:#888;">Online</div>
        </div>
    </div>

</div>

</div>

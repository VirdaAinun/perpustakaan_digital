@extends('layouts.backend.admin.app')

@section('content')
<style>
.profile-wrapper {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f0f2f5;
    padding: 20px;
}

.profile-card {
    background: white;
    border-radius: 20px;
    width: 100%;
    max-width: 700px;
    display: flex;
    flex-wrap: wrap;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
}

.profile-left {
    flex: 1;
    background: #e8f0fe;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    min-width: 250px;
}

.avatar-wrapper {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: #dbeafe;
    margin-bottom: 20px;
    overflow: hidden;
    border: 4px solid white;
}

.avatar-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-right {
    flex: 1.5;
    padding: 40px;
    min-width: 300px;
}

.profile-right h3 {
    margin-bottom: 25px;
    font-weight: 600;
    color: #0f172a;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-control-custom {
    width: 100%;
    padding: 12px 15px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
}

.form-control-custom:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}

.btn-save {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    margin-top: 10px;
    cursor: pointer;
    transition: 0.3s;
}

.btn-save:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

.logout-box {
    position: absolute;
    top: 20px;
    right: 20px;
}

.btn-logout {
    background: #f87171;
    color: white;
    padding: 8px 15px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
}

.btn-logout:hover {
    background: #dc2626;
}
</style>

<div class="profile-wrapper">
    <div class="profile-card">
        <div class="logout-box">
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirmLogout()">
    @csrf
    <button type="submit" class="btn-logout">
        <span>🚪</span> Logout
    </button>
</form>
        </div>

        <div class="profile-left">
            <div class="avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=93c5fd&color=0f172a&size=150" alt="Avatar">
            </div>
        </div>

        <div class="profile-right">
            <h3>Profile Petugas</h3>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control-custom" value="{{ $user->name }}" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control-custom" value="{{ $user->email }}" readonly>
            </div>

            <div class="form-group">
                <label>Role</label>
                <input type="text" class="form-control-custom" value="{{ ucfirst($user->role) }}" readonly>
            </div>
        </div>
    </div>
</div>
<script>
function confirmLogout() {
    return confirm('Apakah Anda yakin ingin logout?');
}
</script>
@endsection
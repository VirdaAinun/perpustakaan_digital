<a href="{{ route('superadmin.profilekepala') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: #333;">
    <div style="width: 36px; height: 36px; border-radius: 50%; background: #34495e; display: flex; align-items: center; justify-content: center; font-weight: 500; color: white;">
        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
    </div>
    <div>
        <div style="font-weight: 500;">Kepala Perpustakaan</div>
        <div style="font-size: 12px; color: #888;">Super Admin</div>
    </div>
</a>
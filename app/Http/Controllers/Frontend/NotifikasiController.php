<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function baca($id)
    {
        Notifikasi::where('id', $id)->where('user_id', auth()->id())->update(['dibaca' => true]);
        return back();
    }

    public function bacaSemua()
    {
        Notifikasi::where('user_id', auth()->id())->update(['dibaca' => true]);
        return back();
    }
}

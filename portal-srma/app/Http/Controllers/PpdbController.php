<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class PpdbController extends Controller
{
    public function index()
    {
        $ppdbPoster = Profile::getValue('ppdb_poster_image', '');
        $ppdbExtraInfo = Profile::where('key', 'ppdb_extra_info')->value('content_2') ?? '';

        return view('public.ppdb', compact('ppdbPoster', 'ppdbExtraInfo'));
    }
}

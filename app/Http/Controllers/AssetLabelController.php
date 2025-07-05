<?php

namespace App\Http\Controllers;

use App\Models\Asset; // Sesuaikan dengan model kamu
use Illuminate\Http\Request;

class AssetLabelController extends Controller
{
    public function show($id)
    {
        $asset = Asset::findOrFail($id);

        return view('label.asset', compact('asset'));
    }
}

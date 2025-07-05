<?php

namespace App\Http\Controllers;

use App\Models\Asset;

class AssetPublicController extends Controller
{
    public function show($id)
    {
        $asset = Asset::with([
            'category',
            'room',
            'user',
            'brand',
            'approvedPurchases',
            'approvedMaintenances',
        ])->findOrFail($id);

        return view('asset.public-show', compact('asset'));
    }
}

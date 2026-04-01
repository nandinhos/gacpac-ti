<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\InventoryAsset;
use App\Models\InventoryRecord;
use App\Models\UncataloguedItem;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryReportController extends Controller
{
    public function download(InventoryRecord $inventory)
    {
        $foundAssetIds = InventoryAsset::where('inventory_id', $inventory->id)->pluck('asset_id');
        $foundAssets = Asset::whereIn('id', $foundAssetIds)->get();

        $query = Asset::query();
        if ($inventory->sector_id) {
            $query->where('sector_id', $inventory->sector_id);
        }
        $pendingAssets = $query->whereNotIn('id', $foundAssetIds)->get();

        $uncataloguedItems = UncataloguedItem::where('inventory_id', $inventory->id)->get();

        $pdf = Pdf::loadView('inventory.pdf', [
            'inventory' => $inventory->load(['sector', 'responsibleUser', 'reopenHistory.reopenedBy']),
            'foundAssets' => $foundAssets,
            'pendingAssets' => $pendingAssets,
            'uncataloguedItems' => $uncataloguedItems,
        ]);

        $filename = 'inventario-'.str_replace(['/', '\\'], '-', $inventory->commission_number).'.pdf';

        return $pdf->download($filename);
    }
}

<?php

namespace App\Http\Controllers\AdminP2mp;

use App\Http\Controllers\Controller;
use App\Models\PengisianBukti;
use Illuminate\Http\Request;

class BulkApproveController extends Controller
{
    /**
     * Setujui semua bukti IKU/IKT yang pending (Bulk Approval).
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = json_decode($request->ids, true);
        
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada bukti IKU/IKT yang dipilih untuk disetujui.');
        }

        $updated = PengisianBukti::whereIn('id', $ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'valid',
                'catatan_validator' => null,
            ]);

        return redirect()->back()->with('success', "Total {$updated} bukti IKU/IKT berhasil disetujui.");
    }
}

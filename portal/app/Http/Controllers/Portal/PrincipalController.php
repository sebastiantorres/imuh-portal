<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PrincipalController extends Controller
{
    public function index(Request $request)
    {
        $token = session('portal_token');
        $base  = config('services.portal.base_uri');

        $docsResponse = Http::withToken($token)
            ->get("{$base}/docs/pending");
        $pendingCount = 0;
        if ($docsResponse->successful()) {
            $documents = collect($docsResponse->json());
            $pendingCount = $documents->count();
        }

        $fundingResponse = Http::withToken($token)
            ->get("{$base}/funding");

        $balance = $fundingResponse->successful()
        ? ($fundingResponse->json()['balance'] ?? null)
        : null;
        $currentInstallment = $fundingResponse->successful()
        ? ($fundingResponse->json()['installment'] ?? null)
        : null;

        return view('portal.dashboard', [
            'pendingCount'       => $pendingCount,
            'currentInstallment' => $currentInstallment,
            'balance'            => $balance,
        ]);
    }
}

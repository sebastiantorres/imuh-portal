<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $token = session('portal_token');
        $base  = config('services.portal.base_uri');

        $docsResponse = Http::withToken($token)
            ->get("{$base}/docs/pending");
        $pendingCount = 0;
        if ($docsResponse->successful()) {
            $documents    = collect($docsResponse->json());
            $pendingCount = $documents->count();
        }

        $fundingResponse = Http::withToken($token)
            ->get("{$base}/funding");

        $funding = $fundingResponse->successful()
        ? $fundingResponse->json()['funding'] ?? null
        : null;
        $funding_group = $fundingResponse->successful()
        ? $fundingResponse->json()['funding_group'] ?? null
        : null;
        $allocation = $fundingResponse->successful()
        ? $fundingResponse->json()['allocation'][0] ?? null
        : null;
        return view('portal.account', [
            'funding'       => $funding,
            'funding_group' => $funding_group,
            'allocation'    => $allocation,
        ]);
    }
}

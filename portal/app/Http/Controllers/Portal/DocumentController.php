<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DocumentController extends Controller
{
    public function index(Request $req)
    {
        $token = session('portal_token');
        $response = Http::timeout(config('services.portal.timeout'))
            ->withToken($token)
            ->get(config('services.portal.base_uri').'/docs'); // Asume que tu API expone `/docs`

        abort_if($response->status() !== 200, 403, 'No autorizado');

        $documents = $response->json(); // Array con ruta / nombre
        return view('portal.documents.index', compact('documents'));
    }
}

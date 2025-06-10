<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DocumentController extends Controller
{
    public function index()
    {
        $token = session('portal_token');
        $base  = config('services.portal.base_uri');
        $response = Http::withToken($token)
            ->get("{$base}/docs/pending");

        // Convertimos a colección para usar métodos de Blade
        if ($response->successful()) {
            $requirements = collect($response->json());
        } else {
            $requirements = collect();
        }

        return view('portal.documents.index', ['requirements' => $requirements]);
    }

    public function upload(Request $request, int $requirementId)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // max 5MB
        ]);

        $token = session('portal_token');
        $base  = config('services.portal.base_uri');

        $response = Http::withToken($token)
            ->attach(
                'file',
                fopen($request->file('file')->getRealPath(), 'r'),
                $request->file('file')->getClientOriginalName()
            )
            ->post("{$base}/docs/{$requirementId}/upload");

        if ($response->status() === 201) {
            return redirect()->route('portal.documents')
                ->with('success', 'Documento subido correctamente.');
        }

        return back()
            ->withErrors(['file' => 'Error al subir el documento.'])
            ->withInput();
    }
}

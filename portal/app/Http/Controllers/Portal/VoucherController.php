<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VoucherController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'installment_id' => 'required|integer',
            'file'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'observation'    => 'nullable|string|max:1000',
        ]);

        $file   = $request->file('file');
        $base64 = base64_encode(file_get_contents($file->getRealPath()));
        $mime   = $file->getMimeType();

        $token = session('portal_token');            // Asegurate de tenerlo en sesión
        $base  = config('services.portal.base_uri'); // Endpoint de la API

        $response = Http::withToken($token)
            ->post("$base/vouchers", [
                'installment_id' => $request->installment_id,
                'file_base64'    => "data:$mime;base64,$base64",
                'file_name'      => $file->getClientOriginalName(),
                'observation'    => $request->input('observation'),
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Comprobante enviado correctamente.');
        }
        \Log::error('Error al enviar comprobante', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
        return back()->withErrors(['error' => 'Error al enviar comprobante.']);
    }

    public function uploadUnassigned(Request $request)
    {
        $request->validate([
            'file'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'observation' => 'nullable|string|max:1000',
        ]);

        $file   = $request->file('file');
        $base64 = base64_encode(file_get_contents($file->getRealPath()));
        $mime   = $file->getMimeType();

        $token = session('portal_token');
        $base  = config('services.portal.base_uri');

        $response = Http::withToken($token)
            ->post("$base/vouchers/unassigned", [
                'file_base64' => "data:$mime;base64,$base64",
                'file_name'   => $file->getClientOriginalName(),
                'observation' => $request->input('observation'),
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Tu comprobante fue enviado correctamente y será revisado.');
        }

        return back()->withErrors(['error' => 'No se pudo enviar el comprobante.']);
    }
}

@if($installment->status !== 'paid')
    <form method="POST" action="{{ route('portal.vouchers.upload') }}" enctype="multipart/form-data" class="mt-4 p-4 bg-white rounded shadow">
        @csrf

        <input type="hidden" name="installment_id" value="{{ $installment->id }}">

        <div class="mb-4">
            <label class="block font-semibold mb-1">Archivo del comprobante</label>
            <input type="file" name="file" accept="application/pdf,image/*" required class="block w-full border rounded p-2" />
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Observación (opcional)</label>
            <textarea name="observation" rows="3" class="block w-full border rounded p-2"></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Enviar comprobante
        </button>
    </form>
@endif
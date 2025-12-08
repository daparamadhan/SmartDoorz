<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http as HttpClient;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Helpers\QrCodeGenerator;
use App\Services\BrevoService;

class BarcodeController extends Controller
{
    public function index()
    {
        $barcodes = Barcode::with('user')->paginate(15);
        return view('barcode.index', compact('barcodes'));
    }

    public function create()
    {
        $users = User::all();
        return view('barcode.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:barcodes,code',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        Barcode::create($validated);

        return redirect()->route('barcode.index')->with('success', 'Barcode berhasil ditambahkan');
    }

    public function edit(Barcode $barcode)
    {
        $users = User::all();
        return view('barcode.edit', compact('barcode', 'users'));
    }

    public function update(Request $request, Barcode $barcode)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:barcodes,code,' . $barcode->id,
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $barcode->update($validated);

        return redirect()->route('barcode.index')->with('success', 'Barcode berhasil diperbarui');
    }

    public function destroy(Barcode $barcode)
    {
        $barcode->delete();
        return redirect()->route('barcode.index')->with('success', 'Barcode berhasil dihapus');
    }

    /**
     * Generate a QR image for the user, store it privately and send a signed download link via Brevo.
     * Accessible by admins via POST /barcode/{user}/send-link
     */
    public function generateAndSend(Request $request, User $user)
    {
        // generate unique code and get image URL
        $code = QrCodeGenerator::generateQrCode();
        $imageUrl = QrCodeGenerator::getQrImageUrl($code);

        // fetch image content
        $res = HttpClient::get($imageUrl);
        if (! $res->successful()) {
            return back()->with('error', 'Gagal menghasilkan barcode (fetch error)');
        }

        $content = $res->body();
        $filename = Str::uuid()->toString() . '.png';
        $relativeDir = "private/barcodes/{$user->id}";
        $relativePath = "{$relativeDir}/{$filename}";

        // ensure directory and store
        Storage::put($relativePath, $content);

        // create signed URL (valid 24 hours)
        $signedUrl = URL::temporarySignedRoute('barcode.download', now()->addHours(24), [
            'user' => $user->id,
            'file' => $filename,
        ]);

        // send email via Brevo
        $brevo = new BrevoService();
        $html = "<p>Halo {$user->name},</p><p>Silakan unduh barcode Anda melalui tautan berikut (berlaku 24 jam):</p>";
        $html .= "<p><a href=\"{$signedUrl}\">Download Barcode</a></p>";

        $sent = $brevo->sendBarcodeLink($user->email, $user->name, 'Barcode Anda dari SmartDoorz', $html);

        if (! $sent) {
            return back()->with('error', 'Barcode tersimpan tetapi email gagal dikirim');
        }

        return back()->with('success', 'Barcode dihasilkan dan tautan dikirim ke pengguna');
    }

    /**
     * Download barcode file via signed URL.
     * Public route but only valid with signed URL.
     */
    public function download(Request $request, $userId, $file)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $path = storage_path("app/private/barcodes/{$userId}/{$file}");
        if (! file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $file, [
            'Content-Type' => 'image/png'
        ]);
    }
}

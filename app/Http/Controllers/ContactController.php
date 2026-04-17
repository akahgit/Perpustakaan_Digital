<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * SISI ANGGOTA: Tampilkan Halaman Kontak
     */
    public function showContactForm()
    {
        return view('pages.kontak');
    }

    /**
     * SISI ANGGOTA: Kirim Pesan ke Petugas
     */
    public function storeMessage(Request $request)
    {
        $validated = $request->validate([
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'nama' => Auth::user()->name,
            'email' => Auth::user()->email,
            'subjek' => $validated['subjek'],
            'pesan' => $validated['pesan'],
            'status' => 'unread',
        ]);

        return back()->with('success', 'Pesan Anda telah berhasil dikirim ke Petugas. Terima kasih!');
    }

    /**
     * SISI PETUGAS: Tampilkan Semua Daftar Kontak
     */
    public function indexPetugas()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(10);
        
        // Tandai yang ditampilkan sebagai 'read' secara otomatis (opsional)
        // Contact::where('status', 'unread')->update(['status' => 'read']);

        return view('petugas.kontak.index', compact('contacts'));
    }

    /**
     * SISI PETUGAS: Update Status Baca
     */
    public function markAsRead(Contact $contact)
    {
        $contact->update(['status' => 'read']);
        return back()->with('success', 'Pesan ditandai sebagai telah dibaca.');
    }

    /**
     * SISI PETUGAS: Hapus Pesan
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}

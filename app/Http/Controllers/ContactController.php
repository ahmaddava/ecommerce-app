<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Menerima dan menyimpan pesan dari form kontak.
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'privacy' => 'accepted', // Pastikan checkbox disetujui
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        ContactMessage::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda berhasil dikirim! Kami akan segera merespon.'
        ]);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        // Tandai pesan sebagai 'read' saat dibuka
        if ($message->status === 'new') {
            $message->update(['status' => 'read']);
        }
        return view('admin.messages.show', compact('message'));
    }
}
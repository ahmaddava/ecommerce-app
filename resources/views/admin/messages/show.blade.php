@extends('layouts.app')

@section('title', 'Detail Pesan')

@section('content')
<div class="container">
    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Pesan Masuk
    </a>
    <h1 class="text-gradient">Detail Pesan</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="mb-0">{{ $message->subject }}</h5>
                    <small class="text-muted">Dari: {{ $message->name }} &lt;{{ $message->email }}&gt;</small>
                </div>
                <small>{{ $message->created_at->format('d M Y, H:i') }}</small>
            </div>
        </div>
        <div class="card-body">
            <p><strong>Telepon:</strong> {{ $message->phone ?: '-' }}</p>
            <hr>
            <p style="white-space: pre-wrap;">{{ $message->message }}</p>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Pesan Masuk')

@section('content')
<div class="container">
    <h1 class="text-gradient"><i class="bi bi-envelope-paper"></i> Pesan Masuk</h1>
    <p class="text-muted">Daftar semua pesan yang dikirim melalui form kontak.</p>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Pengirim</th>
                            <th>Subjek</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr class="{{ $message->status == 'new' ? 'fw-bold' : '' }}">
                                <td>
                                    <span class="badge bg-{{ $message->status == 'new' ? 'primary' : 'secondary' }}">
                                        {{ ucfirst($message->status) }}
                                    </span>
                                </td>
                                <td>{{ $message->name }}</td>
                                <td>{{ $message->subject }}</td>
                                <td>{{ $message->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada pesan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
@extends('panel::layouts.app')
@section('title', 'Daftar Titipan Masuk')

@section('content')
<div class="card">
    <div class="card-body">

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ $active_tab == 'pending' ? 'active' : '' }}" href="{{ panel_route('submissions.index', ['tab' => 'pending']) }}">
                    Perlu Diproses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $active_tab == 'history' ? 'active' : '' }}" href="{{ panel_route('submissions.index', ['tab' => 'history']) }}">
                    Riwayat
                </a>
            </li>
        </ul>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Nama Produk</td>
                        <td>Penitip</td>
                        <td>Harga</td>
                        <td>Status</td>
                        <td>Tanggal</td>
                        <td>Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                    <tr>
                        <td>{{ $submission->id }}</td>
                        <td>{{ $submission->product_name }}</td>
                        <td>{{ $submission->user->name ?? 'N/A' }}</td>
                        <td>{{ currency_format($submission->price) }}</td>
                        <td>
                            @php
                                $status_classes = [
                                    'pending' => 'bg-warning',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'revision_needed' => 'bg-info text-dark',
                                ];
                                $status_class = $status_classes[$submission->status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $status_class }}">{{ panel_trans('submission.status_' . $submission->status) }}</span>
                        </td>   
                        <td>{{ $submission->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ panel_route('submissions.show', $submission) }}" class="btn btn-sm btn-primary">Review</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        @if($active_tab == 'pending')
                            <td colspan="7" class="text-center">Tidak ada titipan yang perlu diproses.</td>
                        @else
                            <td colspan="7" class="text-center">Tidak ada riwayat titipan.</td>
                        @endif
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $submissions->appends(request()->query())->links('panel::vendor/pagination/bootstrap-4') }}
    </div>
</div>
@endsection
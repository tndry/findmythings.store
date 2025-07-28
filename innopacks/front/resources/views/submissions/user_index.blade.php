@extends('layouts.app')
@section('title', 'Titipan Saya')

@section('content')
    <x-front-breadcrumb type="route" value="account.submissions.index" title="Titipan Saya"/>

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3">
                @include('shared.account-sidebar')
            </div>
            <div class="col-12 col-lg-9">
                <div class="account-card-box order-box">
                    <div class="account-card-title d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Titipan Saya</span>
                    </div>

                    {{-- Navigasi Tab Status (Sudah Bilingual) --}}
                    <ul class="nav nav-tabs tabs-plus">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('status') ? 'active' : '' }}"
                               href="{{ account_route('submissions.index') }}">Semua</a>
                        </li>
                        @foreach ($filter_statuses as $status_key)
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == $status_key ? 'active' : '' }}"
                                href="{{ account_route('submissions.index', ['status' => $status_key]) }}">
                                    {{ front_trans('submission.status_' . $status_key) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Form Pencarian --}}
                    <form method="GET" action="{{ account_route('submissions.index') }}" class="mb-3 d-flex" style="max-width: 400px;">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input
                            type="text"
                            name="search"
                            class="form-control me-2"
                            placeholder="Cari nama produk..."
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </form>

                    {{-- Tabel Daftar Titipan (Sudah Bilingual) --}}
                    @if ($submissions->count())
                        <table class="table align-middle account-table-box table-response">
                            <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($submissions as $submission)
                                <tr>
                                    <td data-title="Produk">{{ $submission->product_name }}</td>
                                    <td data-title="Harga">{{ currency_format($submission->price) }}</td>
                                    <td data-title="Tanggal">{{ $submission->created_at->format('Y-m-d') }}</td>
                                    <td data-title="Status">
                                        @php
                                            $status_classes = [
                                                'pending' => 'bg-warning',
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger',
                                                'revision_needed' => 'bg-info text-dark',
                                            ];
                                            $status_class = $status_classes[$submission->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $status_class }}">{{ front_trans('submission.status_' . $submission->status) }}</span>
                                    </td>
                                    <td data-title="Aksi">
                                        @if ($submission->status == 'revision_needed')
                                            <a href="{{ account_route('submissions.edit', $submission) }}" class="btn btn-primary btn-sm">Perbaiki</a>
                                        @elseif($submission->status == 'approved')
                                            <form action="{{ account_route('submissions.mark_as_sold', $submission) }}" method="POST" onsubmit="return confirm('Anda yakin barang ini sudah terjual? Aksi ini tidak dapat dibatalkan.');">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm">Tandai Terjual</button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                 @if ($submission->status == 'revision_needed' && $submission->admin_notes)
                                    <tr class="table-info">
                                        <td colspan="5" style="font-size: 0.9em;">
                                            <strong>Catatan dari Admin:</strong> {{ $submission->admin_notes }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        {{ $submissions->appends(request()->query())->links('panel::vendor/pagination/bootstrap-4') }}
                    @else
                        <div class="text-center p-4">
                            <p>Tidak ada data titipan yang ditemukan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
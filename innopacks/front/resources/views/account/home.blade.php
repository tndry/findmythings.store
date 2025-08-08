@extends('layouts.app')
@section('body-class', 'page-account')

@section('content')
    <x-front-breadcrumb type="route" value="account.index" title="{{ __('front/account.account') }}"/>

    @hookinsert('account.home.top')

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3">
                @include('shared.account-sidebar')
            </div>
            <div class="col-12 col-lg-9">
                <div class="account-card-box account-info">
                    <div class="account-card-title d-flex justify-content-between align-items-center">
                        <span class="fw-bold">{{ __('front/account.hello') }}, {{ $customer->name }}</span>
                        <a href="{{ account_route('edit.index') }}" class="text-secondary">{{ __('front/account.edit') }} <i class="bi bi-arrow-right"></i></a>
                    </div>

                    <div class="account-data">
                        <div class="row">
                            <div class="col-6 col-md-4">
                                <a href="{{ account_route('submissions.index', ['status' => 'pending']) }}" class="account-item-data">
                                    <div class="value">{{ $pending_submissions_count ?? 0 }}</div>
                                    <div class="title text-secondary">{{ front_trans('account.pending_approval') }}</div>
                                </a>
                            </div>
                            <div class="col-6 col-md-4">
                                <a href="{{ account_route('submissions.index', ['status' => 'revision_needed']) }}" class="account-item-data">
                                    <div class="value">{{ $revision_submissions_count ?? 0 }}</div>
                                    <div class="title text-secondary">{{ front_trans('account.revision_needed') }}</div>
                                </a>
                            </div>
                            <div class="col-6 col-md-4">
                                <a href="{{ account_route('submissions.index', ['status' => 'approved']) }}" class="account-item-data">
                                    <div class="value">{{ $approved_submissions_count ?? 0 }}</div>
                                    <div class="title text-secondary">{{ front_trans('account.active_submissions') }}</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="account-card-title d-flex justify-content-between align-items-center">
                        <span class="fw-bold">{{ front_trans('account.your_latest_submissions') }}</span>
                        <a href="{{ account_route('submissions.index') }}" class="text-secondary">{{ __('front/account.view_all') }} <i class="bi bi-arrow-right"></i></a>
                    </div>

                    @if($latest_submissions->count())
                        <table class="table align-middle account-table-box table-response">
                            <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Tanggal Titip</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($latest_submissions as $submission)
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
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-4 text-center">
                            {{ front_trans('account.no_submissions_yet') }} 
                            @if(auth('customer')->check())
                                <a href="{{ route('submission.create') }}" class="btn btn-primary btn-sm">{{ front_trans('account.submit_now') }}</a>
                            @else
                                <a href="{{ front_route('login.index') }}" class="btn btn-primary btn-sm">Login untuk Mulai Jual</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @hookinsert('account.home.bottom')

@endsection
@extends('layouts.master')
@section('content')
@section('scss')
<style>
    .custom-label {
        font-size: 0.9rem; /* Adjust font size */
        line-height: 1.5; /* Adjust line height */
    }
    .custom-label svg {
        vertical-align: text-bottom; /* Align SVG to the bottom of the text */
        margin-right: 1px; /* Reduced spacing between SVG and text */
    }
</style>
@endsection
<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
              <div class="col-6">
                <h3>List Server {{ $categoryData->name }}</h3>
              </div>
              <div class="col-6">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">                                       
                  <li class="breadcrumb-item">Dashboard</li>
                  <li class="breadcrumb-item active"> List Server {{ $categoryData->name }}</li>
                </ol>
              </div>
            </div>
          </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            @forelse ($servers as $server)
                <div class="col-12 col-md-4 mb-3">
                    <div class="card" style="padding: 10px;">
                        <div class="card-body p-2">
                            <div class="text-center">
                                <h4 class="f-light d-block mb-1">{{ Str::upper($server->name) }}</h4>
                                <span class="badge bg-primary fw-bold">{{ $server->country->name }}</span>
                                {!! $server->status == 'online' ? '<label class="badge badge-light-success">Online</label>' : '<label class="badge badge-light-danger">Offline</label>' !!}
                                {{-- <hr> --}}
                            </div>
                            <ul class="text-start">
                                <li><i class="icofont icofont-hand-drawn-right"></i> Host : <b>{{ $server->host }}</b></li>
                                <li><i class="icofont icofont-hand-drawn-right"></i> ISP : <b> {{ $server->isp }}</b></li>
                                <li><i class="icofont icofont-hand-drawn-right"></i> Price Monthly : <b>IDR. {{ $server->prices->where('role_id', Auth::user()->roles->first()->id)->first() ? number_format($server->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_monthly, 2) : 'N/A' }}</b></li>
                                <li><i class="icofont icofont-hand-drawn-right"></i> Price Hourly : <b>IDR. {{ $server->prices->where('role_id', Auth::user()->roles->first()->id)->first() ? number_format($server->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_hourly, 2) : 'N/A' }}</b></li>
                                
                                    @foreach ($server->ports as $key => $value)
                                        <li><i class="icofont icofont-hand-drawn-right"></i> {{ $key }} : <b>{{ $value }}</b></li>
                                    @endforeach
                                
                                <li><i class="icofont icofont-hand-drawn-right"></i> SlowDNS : <b> <span class="badge badge-light-success">Support</span></b></li>
                                <li><i class="icofont icofont-hand-drawn-right"></i> UDP : <b> <span class="badge badge-light-success">Support</span></b></li>
                                <li><i class="icofont icofont-hand-drawn-right"></i> Max Device : <b> <span class="badge badge-light-warning">2 Session</span></b></li>
                                <li><i class="icofont icofont-hand-drawn-right"></i> Torrent : <b> <span class="badge badge-light-danger">Not Allow</span></b></li>
                                <li class="pricing-list-item">
                                <div class="text-center mt-3">
                                <label class="badge badge-primary custom-label">
                                    <svg viewBox="0 0 24 24" width="12" height="10" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg> 
                                    {{ round(($server->current / $server->limit) * 100, 2) }}% 
                                    {{ round(($server->current / $server->limit) * 100, 2) < 100 ? 'Account Created' : 'Fully Created!' }}
                                </label>
                                <div class="progress sm-progress-bar overflow-visible">
                                    <div class="progress-bar-animated small-progressbar bg-primary rounded-pill progress-bar-striped" role="progressbar" style="width: {{ round(($server->current / $server->limit) * 100, 2) }}%" aria-valuenow="{{ round(($server->current / $server->limit) * 100, 2) }}" aria-valuemin="0" aria-valuemax="100"><span class="animate-circle"></span></div>
                                </div>
                                    </div>
                                </li>
                             </ul>
                            <div class="d-grid gap-2 mt-4">
                                <a href="{{ route('servers.create', [$server->category->slug, $server->slug]) }}" class="btn btn-primary">Select</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            <div class="text-center">
                Server {{ $categoryData->name }} Not Found
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
{{-- resources/views/account/layout.blade.php --}}
@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endsection

@section('content')
<div class="kb-account-page">
    <div class="kb-account-container">

        {{-- SIDEBAR --}}
        <aside class="kb-account-sidebar">
            <div class="kb-account-sidebar-header">
                <i class="bi bi-person-circle kb-account-sidebar-avatar"></i>
                <div class="kb-account-sidebar-user">
                    <span class="kb-account-sidebar-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    <span class="kb-account-sidebar-email">{{ auth()->user()->email }}</span>
                </div>
            </div>

            <nav class="kb-account-nav">
                <a href="{{ route('account.dashboard') }}"
                   class="kb-account-nav-item {{ request()->routeIs('account.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('account.profile') }}"
                   class="kb-account-nav-item {{ request()->routeIs('account.profile') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ route('account.orders') }}"
                   class="kb-account-nav-item {{ request()->routeIs('account.orders*') ? 'active' : '' }}">
                    <i class="bi bi-bag"></i>
                    <span>My Orders</span>
                </a>
                <a href="{{ route('account.addresses') }}"
                   class="kb-account-nav-item {{ request()->routeIs('account.addresses*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>Addresses</span>
                </a>
                <a href="{{ route('account.reviews') }}"
                   class="kb-account-nav-item {{ request()->routeIs('account.reviews*') ? 'active' : '' }}">
                    <i class="bi bi-star"></i>
                    <span>My Reviews</span>
                </a>
                <a href="{{ route('account.security') }}"
                   class="kb-account-nav-item {{ request()->routeIs('account.security*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock"></i>
                    <span>Security</span>
                </a>

                <div class="kb-account-nav-divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="kb-account-nav-item kb-account-logout-btn">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="kb-account-content">
            @if (session('success'))
                <div class="kb-account-alert kb-account-alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="kb-account-alert kb-account-alert-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('account-content')
        </div>
    </div>
</div>
@endsection

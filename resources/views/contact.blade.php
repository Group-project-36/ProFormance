@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')

<div class="contact-wrapper">
    <h1 class="mb-4">Contact Us</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <p><strong>There were some problems with your submission:</strong></p>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}"  class="row g-3">
        @csrf

        <div class="col-md-6">
            <label for="first_name" class="form-label">First Name *</label>
            <input
                type="text"
                id="first_name"
                name="first_name"
                class="form-control @error('first_name') is-invalid @enderror"
                value="{{ old('first_name') }}"
                required
            >
            @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="last_name" class="form-label">Last Name *</label>
            <input
                type="text"
                id="last_name"
                name="last_name"
                class="form-control @error('last_name') is-invalid @enderror"
                value="{{ old('last_name') }}"
                required
            >
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="email_address" class="form-label">E-mail Address *</label>
            <input
                type="email"
                id="email_address"
                name="email_address"
                class="form-control @error('email_address') is-invalid @enderror"
                value="{{ old('email_address') }}"
                required
            >
            @error('email_address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="subject_select" class="form-label">Subject *</label>
            <select
                id="subject_select"
                name="subject_select"
                class="form-select @error('subject_select') is-invalid @enderror"
                required
            >
                <option value="">-- Please choose --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject }}" @selected(old('subject_select') === $subject)>
                        {{ $subject }}
                    </option>
                @endforeach
            </select>
            @error('subject_select')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- <div class="col-md-6">
            <label for="product_search" class="form-label">
                Product (optional)
            </label>
            <input type="hidden" id="product_id" name="product_id" value="{{ old('product_id') }}">
            <input type="hidden" id="variant_id" name="variant_id" value="{{ old('variant_id') }}">
            <input
                type="text"
                id="product_search"
                name="product_search"
                class="form-control @error('product_search') is-invalid @enderror"
                value="{{ old('product_search') }}"
                placeholder="Product name, SKU, etc."
            >
            @error('product_search')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div> --}}

        <div class="col-md-6 position-relative">
            <label for="product_search" class="form-label">Product (optional)</label>

            <input
                type="text"
                id="product_search"
                name="product_search"
                class="form-control @error('product_search') is-invalid @enderror"
                value="{{ old('product_search') }}"
                placeholder="Type product name or SKU"
                autocomplete="off"
            >

            <input type="hidden" id="product_id" name="product_id" value="{{ old('product_id') }}">
            <input type="hidden" id="variant_id" name="variant_id" value="{{ old('variant_id') }}">

            <div id="product_suggestions"
                class="list-group position-absolute w-100"
                style="z-index: 1000; display:none;">
            </div>

            @error('product_search')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <div class="col-md-6">
            <label for="order_id" class="form-label">
                Order ID (optional)
            </label>
            <input
                type="number"
                id="order_id"
                name="order_id"
                class="form-control @error('order_id') is-invalid @enderror"
                value="{{ old('order_id') }}"
                placeholder="If your enquiry is about an order"
            >
            @error('order_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="message_description" class="form-label">Message *</label>
            <textarea
                id="message_description"
                name="message_description"
                rows="6"
                class="form-control @error('message_description') is-invalid @enderror"
                required
            >{{ old('message_description') }}</textarea>
            @error('message_description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                Send Message
            </button>
        </div>
    </form>
</div>
@endsection
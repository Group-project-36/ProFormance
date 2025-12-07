@extends('layouts.app')

@section('content')
<div class="kb-checkout-page container">
    <h1>Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf

        <h2>Shipping details</h2>

        <div>
            <label>Full name</label>
            <input type="text" name="ship_recipient_name" value="{{ old('ship_recipient_name') }}">
            @error('ship_recipient_name') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>House number</label>
            <input type="text" name="ship_house_number" value="{{ old('ship_house_number') }}">
            @error('ship_house_number') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Address line 1</label>
            <input type="text" name="ship_address_line_one" value="{{ old('ship_address_line_one') }}">
            @error('ship_address_line_one') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Address line 2</label>
            <input type="text" name="ship_address_line_two" value="{{ old('ship_address_line_two') }}">
            @error('ship_address_line_two') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>City</label>
            <input type="text" name="ship_city" value="{{ old('ship_city') }}">
            @error('ship_city') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>County</label>
            <input type="text" name="ship_county" value="{{ old('ship_county') }}">
            @error('ship_county') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Postcode</label>
            <input type="text" name="ship_postcode" value="{{ old('ship_postcode') }}">
            @error('ship_postcode') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Country code (e.g. GB)</label>
            <input type="text" name="ship_country_code" value="{{ old('ship_country_code', 'GB') }}">
            @error('ship_country_code') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Phone number</label>
            <input type="text" name="ship_phone_number" value="{{ old('ship_phone_number') }}">
            @error('ship_phone_number') <div>{{ $message }}</div> @enderror
        </div>

        <h2>Order summary</h2>
        <ul>
            @foreach($lines as $line)
                <li>
                    {{ $line['name'] }} × {{ $line['quantity'] }}
                    — £{{ number_format($line['price'] * $line['quantity'], 2) }}
                </li>
            @endforeach
        </ul>

        <p><strong>Total: £{{ number_format($total, 2) }}</strong></p>

        <button type="submit" class="kb-btn kb-btn-primary">Place order</button>
    </form>
</div>
@endsection


Component Architecture
Reusable Sidebar
sidebar.html

Implementation: Currently loaded via fetch in common.js for local previewing.

Backend Task: Please convert this into a Laravel Blade partial (e.g., resources/views/partials/sidebar.blade.php) and use @include or a Blade Component in your layouts.

Active State: The active class logic should be handled by Laravel's Request::is() helper rather than the current JavaScript-based detection.


Monetary Values (Pennies)
Format: All financial data (e.g. total_penny, unit_price_penny) should be handled as integers (pennies) to avoid floating-point inaccuracies.

Frontend Display: The scripts automatically divide these values by 100 for display in GBP/USD.

Customer Management
Names: Customers are mapped using first_name and last_name columns.

Identifiers: The frontend expects email_address and contact_status (Enum: Pending, Solved).

Inventory
Field: Use available_stock for current inventory counts.

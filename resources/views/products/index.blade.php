@extends('layouts.app')

@section('title', 'Products & Services Catalog - CRM Pro')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Products & Inventory</h1>
        <p class="page-subtitle">Manage SaaS licenses, enterprise add-ons, and professional service packages.</p>
    </div>

    <div style="display: flex; gap: 12px;">
        <button type="button" class="btn btn-primary" onclick="openAddProductModal()">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
            <span>Add Product</span>
        </button>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Product / Service</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock / Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 14px;">{{ $p->name }}</div>
                            <div style="font-size: 12px; color: var(--text-muted); max-width: 300px;">{{ $p->description ?: 'No description provided.' }}</div>
                        </td>
                        <td><span class="badge badge-blue">{{ $p->category ?: 'General' }}</span></td>
                        <td style="font-family: var(--font-heading); font-weight: 800; font-size: 15px; color: var(--text-main);">${{ number_format($p->price, 2) }}</td>
                        <td style="font-weight: 600;">{{ $p->stock }} units</td>
                        <td>
                            @if($p->stock <= 0)
                                <span class="badge badge-danger">Out of Stock</span>
                            @elseif($p->stock <= 5)
                                <span class="badge badge-warning">Low Stock</span>
                            @else
                                <span class="badge badge-success">In Stock</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('products.edit', $p->id) }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                                </a>
                                <form action="{{ route('products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger);">
                                        <i data-lucide="trash" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            No products in inventory. Click "Add Product" to create your first listing.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add Product -->
<div class="modal-backdrop" id="addProductModal">
    <div class="modal-box card-p">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">Add New Product</h3>
            <button type="button" onclick="closeAddProductModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <label class="form-label">Product / Service Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Dedicated AI Copilot Endpoint" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="Software SaaS">
                    </div>
                    <div>
                        <label class="form-label">Unit Price ($) *</label>
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="1999" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Available Stock / Capacity *</label>
                    <input type="number" name="stock" class="form-control" value="25" required>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Features, specifications, SLA details..."></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddProductModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddProductModal() {
        document.getElementById('addProductModal').style.display = 'flex';
    }
    function closeAddProductModal() {
        document.getElementById('addProductModal').style.display = 'none';
    }
</script>
@endpush
@endsection
@extends('layouts.app')

@section('title', 'Edit Product - ' . $product->name)

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Back</span>
        </a>
        <h1 class="page-title" style="margin-bottom: 0;">Edit Product Details</h1>
    </div>

    <div class="card card-p">
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" value="{{ $product->category }}">
                    </div>
                    <div>
                        <label class="form-label">Price ($) *</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Stock Units *</label>
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                </div>

                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
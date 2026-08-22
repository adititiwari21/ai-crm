@extends('layouts.app')

@section('title', 'Edit Client - ' . $client->name)

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
        <a href="{{ route('clients.show', $client->id) }}" class="btn btn-secondary btn-sm">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Back</span>
        </a>
        <h1 class="page-title" style="margin-bottom: 0;">Edit Client Details</h1>
    </div>

    <div class="card card-p">
        <form action="/clients/{{ $client->id }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <label class="form-label">Client Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $client->name }}" required>
                </div>

                <div>
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company" class="form-control" value="{{ $client->company }}">
                </div>

                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ $client->email }}">
                </div>

                <div>
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ $client->phone }}">
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" style="color: var(--danger);" onclick="if(confirm('Are you sure you want to permanently delete this client?')) { document.getElementById('deleteClientForm').submit(); }">
                        <i data-lucide="trash" style="width: 14px; height: 14px;"></i>
                        <span>Delete Client</span>
                    </button>
                </div>
            </div>
        </form>

        <form action="/clients/{{ $client->id }}" method="POST" id="deleteClientForm" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
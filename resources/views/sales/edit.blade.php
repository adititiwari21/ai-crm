<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sale - AI CRM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }

        .edit-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 30px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 16px;
        }

        h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
        }

        .back-link {
            text-decoration: none;
            color: #475569;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .back-link:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 16px;
        }

        label {
            margin-bottom: 6px;
            color: #475569;
            font-size: 12.5px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 11px 14px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            color: #0f172a;
            font-size: 13.5px;
            outline: none;
            transition: 0.2s ease;
        }

        input:focus,
        select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }

        .submit-btn {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
            transition: 0.2s ease;
        }

        .submit-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
<div class="edit-card">
    <div class="header">
        <h1>Edit Sale Transaction</h1>
        <a class="back-link" href="{{ route('sales.index') }}">← Back to Sales</a>
    </div>

    <form action="{{ route('sales.update', $sale->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Client *</label>
            <select name="client_id" required>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $sale->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }} ({{ $client->company }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Amount (₹ / $) *</label>
            <input type="number" name="amount" value="{{ $sale->amount }}" step="0.01" min="0" required>
        </div>

        <div class="form-group">
            <label>Sale Date *</label>
            <input type="date" name="sale_date" value="{{ $sale->sale_date }}" required>
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status" required>
                <option value="Paid" {{ $sale->status === 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Pending" {{ $sale->status === 'Pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Update Sale Record</button>
    </form>
</div>
</body>
</html>

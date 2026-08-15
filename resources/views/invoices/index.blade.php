<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Invoices - AI CRM</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            background: #090d16;
            color: #f8fafc;
            min-height: 100vh;
        }

        .container {
            max-width: 1300px;
            margin: auto;
            padding: 35px;
        }


        /* HEADER */

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .page-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            border-radius: 13px;
            font-size: 21px;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.2);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #64748b;
            font-size: 13px;
        }

        .back {
            text-decoration: none;
            color: #cbd5e1;
            background: #111827;
            border: 1px solid #1e293b;
            padding: 10px 16px;
            border-radius: 9px;
            font-size: 13px;
            transition: 0.2s ease;
        }

        .back:hover {
            background: #1a2333;
            color: white;
        }


        /* ERROR */

        .error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
            padding: 15px 18px;
            border-radius: 11px;
            margin-bottom: 22px;
            font-size: 13px;
        }

        .error ul {
            padding-left: 18px;
        }

        .error li {
            margin-bottom: 4px;
        }


        /* BOXES */

        .form-box,
        .table-box {
            background: #111827;
            border: 1px solid #1e293b;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 22px;
        }

        .section-header {
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 17px;
            margin-bottom: 5px;
        }

        .section-header p {
            color: #64748b;
            font-size: 12px;
        }


        /* FORM */

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 7px;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 500;
        }

        input,
        select {
            width: 100%;
            padding: 13px 14px;
            background: #0d1422;
            border: 1px solid #253047;
            border-radius: 9px;
            color: #f8fafc;
            outline: none;
            transition: 0.2s ease;
        }

        input::placeholder {
            color: #64748b;
        }

        input:focus,
        select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
        }

        select option {
            background: #111827;
            color: white;
        }

        .create-btn {
            margin-top: 20px;
            padding: 11px 18px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(135deg, #6366f1, #7c3aed);
            color: white;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.2);
        }


        /* TABLE HEADER */

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-header h2 {
            font-size: 17px;
        }

        .invoice-count {
            background: rgba(139, 92, 246, 0.12);
            color: #a78bfa;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }


        /* TABLE */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 12px;
            text-align: left;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #1e293b;
            white-space: nowrap;
        }

        td {
            padding: 15px 12px;
            color: #cbd5e1;
            font-size: 13px;
            border-bottom: 1px solid #172033;
            white-space: nowrap;
        }

        tbody tr {
            transition: 0.2s ease;
        }

        tbody tr:hover {
            background: #151d2d;
        }

        .invoice-number {
            color: #a78bfa;
            font-weight: 600;
        }

        .client-name {
            color: #f1f5f9;
            font-weight: 500;
        }

        .amount {
            color: #4ade80;
            font-weight: 600;
        }


        /* STATUS */

        .status {
            display: inline-flex;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .paid {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
        }

        .pending {
            background: rgba(251, 146, 60, 0.12);
            color: #fb923c;
        }


        /* ACTIONS */

        .actions {
            white-space: nowrap;
        }

        .edit {
            display: inline-block;
            text-decoration: none;
            color: #60a5fa;
            background: rgba(59, 130, 246, 0.1);
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 11px;
            margin-right: 6px;
        }

        .edit:hover {
            background: rgba(59, 130, 246, 0.18);
        }

        .delete {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: none;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 11px;
            cursor: pointer;
            margin-top: 0;
        }

        .delete:hover {
            background: rgba(239, 68, 68, 0.18);
        }


        /* EMPTY */

        .empty {
            text-align: center;
            color: #64748b;
            padding: 40px 20px;
            font-size: 13px;
        }


        /* RESPONSIVE */

        @media(max-width: 750px) {

            .container {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .header {
                align-items: flex-start;
                gap: 15px;
            }

        }

    </style>

</head>


<body>

<div class="container">


    <!-- HEADER -->

    <div class="header">

        <div class="header-left">

            <div class="page-icon">
                📄
            </div>

            <div>

                <h1>
                    Invoices
                </h1>

                <p class="subtitle">
                    Create, track and manage your invoices
                </p>

            </div>

        </div>


        <a
            class="back"
            href="{{ route('dashboard') }}"
        >
            ← Dashboard
        </a>

    </div>



    <!-- VALIDATION ERRORS -->

    @if ($errors->any())

        <div class="error">

            <ul>

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif



    <!-- CREATE INVOICE -->

    <div class="form-box">

        <div class="section-header">

            <h2>
                Create New Invoice
            </h2>

            <p>
                Enter invoice details and assign it to a client.
            </p>

        </div>


        <form
            action="{{ route('invoices.store') }}"
            method="POST"
        >

            @csrf


            <div class="form-grid">


                <div class="form-group">

                    <label>
                        Client
                    </label>

                    <select
                        name="client_id"
                        required
                    >

                        <option value="">
                            Select Client
                        </option>

                        @foreach($clients as $client)

                            <option value="{{ $client->id }}">
                                {{ $client->name }}
                            </option>

                        @endforeach

                    </select>

                </div>



                <div class="form-group">

                    <label>
                        Invoice Number
                    </label>

                    <input
                        type="text"
                        name="invoice_number"
                        placeholder="INV-001"
                        required
                    >

                </div>



                <div class="form-group">

                    <label>
                        Amount
                    </label>

                    <input
                        type="number"
                        name="amount"
                        placeholder="Enter invoice amount"
                        step="0.01"
                        min="0"
                        required
                    >

                </div>



                <div class="form-group">

                    <label>
                        Invoice Date
                    </label>

                    <input
                        type="date"
                        name="invoice_date"
                        required
                    >

                </div>



                <div class="form-group">

                    <label>
                        Due Date
                    </label>

                    <input
                        type="date"
                        name="due_date"
                    >

                </div>



                <div class="form-group">

                    <label>
                        Status
                    </label>

                    <select
                        name="status"
                        required
                    >

                        <option value="Pending">
                            Pending
                        </option>

                        <option value="Paid">
                            Paid
                        </option>

                    </select>

                </div>


            </div>


            <button
                type="submit"
                class="create-btn"
            >
                + Create Invoice
            </button>

        </form>

    </div>



    <!-- INVOICE LIST -->

    <div class="table-box">


        <div class="table-header">

            <h2>
                All Invoices
            </h2>

            <span class="invoice-count">
                {{ $invoices->count() }} Invoices
            </span>

        </div>


        @if($invoices->count() > 0)


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Invoice
                            </th>

                            <th>
                                Client
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Invoice Date
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($invoices as $invoice)

                        <tr>


                            <td>

                                <span class="invoice-number">

                                    {{ $invoice->invoice_number }}

                                </span>

                            </td>


                            <td>

                                <span class="client-name">

                                    {{ $invoice->client->name }}

                                </span>

                            </td>


                            <td>

                                <span class="amount">

                                    ₹{{ number_format($invoice->amount, 2) }}

                                </span>

                            </td>


                            <td>

                                {{ $invoice->invoice_date }}

                            </td>


                            <td>

                                {{ $invoice->due_date ?? '-' }}

                            </td>


                            <td>

                                @if($invoice->status === 'Paid')

                                    <span class="status paid">
                                        Paid
                                    </span>

                                @else

                                    <span class="status pending">
                                        Pending
                                    </span>

                                @endif

                            </td>


                            <td class="actions">


                                <a
                                    class="edit"
                                    href="{{ route('invoices.edit', $invoice->id) }}"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ route('invoices.destroy', $invoice->id) }}"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        class="delete"
                                        type="submit"
                                        onclick="return confirm('Are you sure you want to delete this invoice?')"
                                    >
                                        Delete
                                    </button>

                                </form>


                            </td>

                        </tr>

                    @endforeach


                    </tbody>

                </table>

            </div>


        @else


            <div class="empty">

                No invoices added yet.

            </div>


        @endif


    </div>


</div>

</body>

</html>
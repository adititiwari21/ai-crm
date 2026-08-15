<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Clients - AI CRM</title>

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
            max-width: 1250px;
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
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 13px;
            font-size: 22px;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.2);
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


        /* FORM */

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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        input {
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

        input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
        }

        .add-btn {
            margin-top: 18px;
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

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.2);
        }


        /* TABLE */

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-header h2 {
            font-size: 17px;
        }

        .client-count {
            background: rgba(99, 102, 241, 0.12);
            color: #a78bfa;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

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
        }

        td {
            padding: 15px 12px;
            color: #cbd5e1;
            font-size: 13px;
            border-bottom: 1px solid #172033;
        }

        tbody tr {
            transition: 0.2s ease;
        }

        tbody tr:hover {
            background: #151d2d;
        }

        .client-name {
            color: #f1f5f9;
            font-weight: 600;
        }

        .company {
            color: #94a3b8;
        }

        .muted {
            color: #475569;
        }


        /* ACTIONS */

        .actions {
            white-space: nowrap;
        }

        .edit-btn {
            display: inline-block;
            text-decoration: none;
            color: #60a5fa;
            background: rgba(59, 130, 246, 0.1);
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 11px;
            margin-right: 6px;
        }

        .edit-btn:hover {
            background: rgba(59, 130, 246, 0.18);
        }

        .delete-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: none;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 11px;
            cursor: pointer;
        }

        .delete-btn:hover {
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

        @media(max-width: 700px) {

            .container {
                padding: 20px;
            }

            .header {
                align-items: flex-start;
                gap: 15px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .header-left {
                align-items: flex-start;
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
                👥
            </div>

            <div>

                <h1>
                    Clients
                </h1>

                <p class="subtitle">
                    Manage and organize your customers
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



    <!-- ADD CLIENT -->

    <div class="form-box">

        <div class="section-header">

            <h2>
                Add New Client
            </h2>

            <p>
                Enter customer details to add them to your CRM.
            </p>

        </div>


        <form
            action="{{ route('clients.store') }}"
            method="POST"
        >

            @csrf


            <div class="form-grid">

                <input
                    type="text"
                    name="name"
                    placeholder="Client Name"
                    required
                >


                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                >


                <input
                    type="text"
                    name="phone"
                    placeholder="Phone Number"
                >


                <input
                    type="text"
                    name="company"
                    placeholder="Company Name"
                >

            </div>


            <button
                type="submit"
                class="add-btn"
            >
                + Add Client
            </button>

        </form>

    </div>



    <!-- CLIENT LIST -->

    <div class="table-box">


        <div class="table-header">

            <h2>
                All Clients
            </h2>

            <span class="client-count">
                {{ $clients->count() }} Clients
            </span>

        </div>


        @if($clients->count() > 0)

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Company
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach($clients as $client)

                        <tr>


                            <td>

                                <span class="client-name">
                                    {{ $client->name }}
                                </span>

                            </td>


                            <td>

                                @if($client->email)

                                    {{ $client->email }}

                                @else

                                    <span class="muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($client->phone)

                                    {{ $client->phone }}

                                @else

                                    <span class="muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($client->company)

                                    <span class="company">
                                        {{ $client->company }}
                                    </span>

                                @else

                                    <span class="muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            <td class="actions">


                                <a
                                    class="edit-btn"
                                    href="{{ route('clients.edit', $client->id) }}"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ route('clients.destroy', $client->id) }}"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        class="delete-btn"
                                        type="submit"
                                        onclick="return confirm('Are you sure you want to delete this client?')"
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

                No clients added yet.

            </div>

        @endif


    </div>


</div>

</body>

</html>
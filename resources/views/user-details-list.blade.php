<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI CRM - User Details</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #090d18;
            color: white;
            padding: 40px;
        }

        .header {
            margin-bottom: 30px;
        }

        h1 {
            font-size: 30px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #8d9ab5;
        }

        .success {
            margin-bottom: 20px;
            padding: 14px 18px;
            border-radius: 10px;
            background: #123526;
            border: 1px solid #1e6b49;
            color: #86efac;
        }

        .card {
            background: #111827;
            border: 1px solid #263452;
            border-radius: 18px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1250px;
            border-collapse: collapse;
        }

        th {
            background: #151e31;
            color: #9ca3af;
            text-align: left;
            padding: 16px;
            font-size: 14px;
            white-space: nowrap;
        }

        td {
            padding: 16px;
            border-top: 1px solid #263452;
            color: #e5e7eb;
            vertical-align: top;
        }

        tr:hover {
            background: #151e31;
        }

        .empty {
            text-align: center;
            padding: 50px;
            color: #8d9ab5;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 8px;
            background: #1e293b;
            color: #93c5fd;
            font-size: 13px;
        }

        .delete-btn {
            border: none;
            padding: 9px 14px;
            border-radius: 8px;
            background: #3b1518;
            color: #fca5a5;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #5c1d22;
        }

        .requirements {
            max-width: 220px;
            line-height: 1.5;
        }

        .analysis {
            min-width: 300px;
            max-width: 380px;
        }

        .analysis-title {
            color: #a5b4fc;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .analysis-description {
            color: #cbd5e1;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .heading-list {
            margin: 0;
            padding-left: 18px;
            color: #dbeafe;
            line-height: 1.5;
        }

        .heading-list li {
            margin-bottom: 5px;
        }

        .no-analysis {
            color: #64748b;
            font-size: 13px;
        }

        .website {
            color: #93c5fd;
            word-break: break-all;
            max-width: 220px;
        }
    </style>
</head>

<body>

<div class="header">

    <h1>User Details</h1>

    <p class="subtitle">
        Customer information and company analysis collected by AI CRM
    </p>

</div>


@if(session('success'))

    <div class="success">
        {{ session('success') }}
    </div>

@endif


<div class="card">

    @if($userDetails->count() > 0)

        <table>

            <thead>

                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Company</th>
                    <th>Website</th>
                    <th>Requirements</th>
                    <th>Company Analysis</th>
                    <th>Action</th>
                </tr>

            </thead>


            <tbody>

                @foreach($userDetails as $user)

                    <tr>

                        <td>
                            <strong>
                                {{ $user->name }}
                            </strong>
                        </td>


                        <td>
                            {{ $user->email }}
                        </td>


                        <td>
                            {{ $user->phone ?? '—' }}
                        </td>


                        <td>

                            <span class="badge">
                                {{ $user->company ?? '—' }}
                            </span>

                        </td>


                        <td>

                            @if($user->website)

                                <div class="website">
                                    {{ $user->website }}
                                </div>

                            @else

                                —
                                
                            @endif

                        </td>


                        <td>

                            <div class="requirements">
                                {{ $user->requirements ?? '—' }}
                            </div>

                        </td>


                        <td>

                            @if(
                                $user->website_title ||
                                $user->website_description ||
                                $user->website_headings
                            )

                                <div class="analysis">

                                    <div class="analysis-title">
                                        {{ $user->website_title ?? 'No title found' }}
                                    </div>


                                    <div class="analysis-description">

                                        {{ $user->website_description ?? 'No description found' }}

                                    </div>


                                    @if($user->website_headings)

                                        <ul class="heading-list">

                                            @foreach(
                                                preg_split(
                                                    "/\r\n|\n|\r/",
                                                    $user->website_headings
                                                ) as $heading
                                            )

                                                @if(trim($heading) !== '')

                                                    <li>
                                                        {{ $heading }}
                                                    </li>

                                                @endif

                                            @endforeach

                                        </ul>

                                    @endif

                                </div>

                            @else

                                <span class="no-analysis">
                                    Not analyzed yet
                                </span>

                            @endif

                        </td>


                        <td>

                            <form
                                method="POST"
                                action="{{ route('user-details.destroy', $user->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this user?');"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="delete-btn"
                                >
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty">
            No user details found.
        </div>

    @endif

</div>

</body>
</html>
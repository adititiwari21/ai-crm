<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Client - AI CRM</title>

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        h1 {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        button {
            padding: 12px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

    </style>

</head>

<body>

<div class="container">

    <a
        href="{{ route('clients.index') }}"
        class="back"
    >
        ← Back to Clients
    </a>


    <div class="box">

        <h1>
            ✏️ Edit Client
        </h1>


        <form
            action="{{ route('clients.update', $client->id) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            <div class="form-group">

                <label>
                    Client Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ $client->name }}"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ $client->email }}"
                >

            </div>


            <div class="form-group">

                <label>
                    Phone
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ $client->phone }}"
                >

            </div>


            <div class="form-group">

                <label>
                    Company
                </label>

                <input
                    type="text"
                    name="company"
                    value="{{ $client->company }}"
                >

            </div>


            <button type="submit">
                Update Client
            </button>

        </form>

    </div>

</div>

</body>

</html>
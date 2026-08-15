<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Product - AI CRM</title>

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

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        textarea {
            min-height: 100px;
        }

        button {
            padding: 12px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="{{ route('products.index') }}" class="back">
        ← Back to Products
    </a>

    <div class="box">

        <h1>✏️ Edit Product</h1>

        <form
            action="{{ route('products.update', $product->id) }}"
            method="POST"
        >

            @csrf

            @method('PUT')

            <div class="form-group">

                <label>
                    Product Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ $product->name }}"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    Category
                </label>

                <input
                    type="text"
                    name="category"
                    value="{{ $product->category }}"
                >

            </div>

            <div class="form-group">

                <label>
                    Price
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="price"
                    value="{{ $product->price }}"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    Stock
                </label>

                <input
                    type="number"
                    name="stock"
                    value="{{ $product->stock }}"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    Description
                </label>

                <textarea name="description">{{ $product->description }}</textarea>

            </div>

            <button type="submit">
                Update Product
            </button>

        </form>

    </div>

</div>

</body>

</html>
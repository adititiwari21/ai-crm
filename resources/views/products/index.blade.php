<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products - AI CRM</title>

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
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            border-radius: 13px;
            font-size: 21px;
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.2);
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


        /* BOX */

        .box {
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
        textarea {
            width: 100%;
            padding: 13px 14px;
            background: #0d1422;
            border: 1px solid #253047;
            border-radius: 9px;
            color: #f8fafc;
            outline: none;
            transition: 0.2s ease;
        }

        input::placeholder,
        textarea::placeholder {
            color: #64748b;
        }

        input:focus,
        textarea:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.08);
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        .description-group {
            grid-column: span 2;
        }

        .add {
            margin-top: 20px;
            padding: 11px 18px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: white;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.2);
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

        .product-count {
            background: rgba(14, 165, 233, 0.12);
            color: #38bdf8;
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

        .product-name {
            color: #f1f5f9;
            font-weight: 600;
        }

        .category {
            display: inline-flex;
            background: rgba(99, 102, 241, 0.1);
            color: #a5b4fc;
            padding: 5px 9px;
            border-radius: 7px;
            font-size: 11px;
        }

        .price {
            color: #4ade80;
            font-weight: 600;
        }

        .stock {
            color: #38bdf8;
            font-weight: 600;
        }


        /* ACTIONS */

        .actions {
            white-space: nowrap;
        }

        .edit {
            display: inline-block;
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            text-decoration: none;
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

        @media(max-width: 700px) {

            .container {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .description-group {
                grid-column: span 1;
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
                📦
            </div>

            <div>

                <h1>
                    Products
                </h1>

                <p class="subtitle">
                    Manage products, pricing and inventory
                </p>

            </div>

        </div>


        <a
            href="{{ route('dashboard') }}"
            class="back"
        >
            ← Dashboard
        </a>

    </div>



    <!-- ADD PRODUCT -->

    <div class="box">

        <div class="section-header">

            <h2>
                Add New Product
            </h2>

            <p>
                Add a product to your CRM inventory.
            </p>

        </div>


        <form
            action="{{ route('products.store') }}"
            method="POST"
        >

            @csrf


            <div class="form-grid">


                <div class="form-group">

                    <label>
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="e.g. Laptop"
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
                        placeholder="e.g. Electronics"
                    >

                </div>



                <div class="form-group">

                    <label>
                        Price
                    </label>

                    <input
                        type="number"
                        name="price"
                        step="0.01"
                        min="0"
                        placeholder="e.g. 50000"
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
                        min="0"
                        placeholder="e.g. 10"
                        required
                    >

                </div>



                <div class="form-group description-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        placeholder="Enter product description"
                    ></textarea>

                </div>


            </div>


            <button
                type="submit"
                class="add"
            >
                + Add Product
            </button>

        </form>

    </div>



    <!-- PRODUCT LIST -->

    <div class="box">


        <div class="table-header">

            <h2>
                All Products
            </h2>

            <span class="product-count">
                {{ $products->count() }} Products
            </span>

        </div>


        @if($products->count())


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Product
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Stock
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($products as $product)

                        <tr>


                            <td>

                                <span class="product-name">

                                    {{ $product->name }}

                                </span>

                            </td>


                            <td>

                                @if($product->category)

                                    <span class="category">
                                        {{ $product->category }}
                                    </span>

                                @else

                                    <span>
                                        -
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="price">

                                    ₹{{ number_format($product->price, 2) }}

                                </span>

                            </td>


                            <td>

                                <span class="stock">

                                    {{ $product->stock }}

                                </span>

                            </td>


                            <td class="actions">


                                <a
                                    href="{{ route('products.edit', $product->id) }}"
                                    class="edit"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ route('products.destroy', $product->id) }}"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="delete"
                                        onclick="return confirm('Delete this product?')"
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

                No products found.

            </div>


        @endif


    </div>


</div>

</body>

</html>
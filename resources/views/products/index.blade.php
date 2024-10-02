<!-- resources/views/products/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        body {
            font-family: monospace;
            margin: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn-sm {
            padding: 5px 10px;
        }

        .btn-danger {
            background-color: #e0241b;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
        }
    </style>
</head>
<body>
    <h1>Products</h1>

    <a href="{{ route('products.create') }}" class="btn">Add New Product</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Cost</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ Str::limit($product->product_description, 50) }}</td>
                    <td>{{ $product->cost }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

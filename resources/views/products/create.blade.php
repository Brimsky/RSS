<!-- resources/views/products/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Product</title>
    <style>
        body {
            font-family: monospace;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Create New Product</h1>
    </header>

    @if ($errors->any())
        <div class="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required>

            <label for="product_description">Product Description:</label>
            <textarea id="product_description" name="product_description" rows="4" required></textarea>

            <label for="cost">Cost:</label>
            <input type="number" id="cost" name="cost" step="0.01" min="0" required>

            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="3"></textarea>

            <button type="submit">Create Product</button>
        </form>
    </div>
</body>
</html>

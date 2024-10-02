<!-- resources/views/products/edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
    </style>
</head>
<body>
    <header class="header">
        <h1>Edit Product</h1>
    </header>

    <div class="form-container">
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="{{ $product->product_name }}" required>

            <label for="product_description">Product Description:</label>
            <textarea id="product_description" name="product_description" rows="4" required>{{ $product->product_description }}</textarea>

            <label for="cost">Cost:</label>
            <input type="number" id="cost" name="cost" value="{{ $product->cost }}" step="0.01" min="0" required>

            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="3">{{ $product->review }}</textarea>

            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>

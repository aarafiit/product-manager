<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
        }

        button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .image-preview {
            margin-top: 10px;
        }

        .image-preview img {
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
<h1>Edit a Product</h1>
<form method="POST" action="{{ route('product.update', ['product' => $product->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required value="{{ old('name', $product->name) }}">
    </div>

    <div>
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
    </div>

    <div>
        <label for="qty">Quantity:</label>
        <input type="number" id="qty" name="qty" min="1" value="{{ old('qty', $product->qty) }}" required>
    </div>

    <div>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
    </div>

    <div>
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <option value="">Select a category</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ $category->id == old('category_id', $product->category_id) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
        @if ($product->image)
        <div class="image-preview">
            <img src="{{ $product->getImageUrlAttribute() }}" alt="Product Image">
        </div>
        @endif
    </div>

    <div>
        <button type="submit">Update</button>
    </div>
</form>

<script>
    function previewImage(event) {
        const fileInput = event.target;
        const preview = document.querySelector('.image-preview');
        const file = fileInput.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (!preview) {
                    const previewContainer = document.createElement('div');
                    previewContainer.classList.add('image-preview');
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                    fileInput.parentNode.appendChild(previewContainer);
                } else {
                    const img = preview.querySelector('img');
                    img.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        } else {
            if (preview) {
                preview.remove();
            }
        }
    }
</script>
</body>
</html>

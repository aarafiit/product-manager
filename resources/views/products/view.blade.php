<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Product</title>
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

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
        }

        .modal-header {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .modal-close {
            cursor: pointer;
            color: red;
            float: right;
        }

        .modal-footer {
            margin-top: 20px;
            text-align: right;
        }

        .modal-footer button {
            margin-left: 10px;
        }

        #category-message {
            color: red;
            display: none;
        }
    </style>
</head>
<body>
<h1>Create a Product</h1>
<form id="productForm" method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="2" required></textarea>
    </div>
    <div>
        <label for="qty">Quantity:</label>
        <input type="number" id="qty" name="qty" min="1" required>
    </div>
    <div>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" min="0" required>
    </div>
    <div>
        <label for="category">Category:</label>
        <select id="category" name="category_id" required>
            <option value="">Select a category</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <div><button type="button" id="add-category-btn" onclick="openModal()">Add Category</button></div>
    </div>
    <div>
        <label for="image">Image:</label>
        <input type="file" id="image" name="image">
    </div>
    <div>
        <button type="submit">Create Product</button>
    </div>
</form>

<div id="addCategoryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            Add Category
            <span class="modal-close" onclick="closeModal()">&times;</span>
        </div>
        <form id="addCategoryForm" method="POST" action="{{ route('category.store') }}">
            @csrf
            <div>
                <label for="category-name">Category Name:</label>
                <input type="text" id="category-name" name="name" required>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal()">Cancel</button>
                <button type="submit">Add</button>
            </div>
        </form>
        <div id="category-message"></div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('addCategoryModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('addCategoryModal').style.display = 'none';
    }

    document.getElementById('addCategoryForm').onsubmit = async function (e) {
        e.preventDefault();
        const categoryName = document.getElementById('category-name').value;

        // Debugging log
        console.log("Submitting category:", { name: categoryName });

        const response = await fetch("{{ route('category.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ name: categoryName }),
        });

        const result = await response.json();

        // Debugging log
        console.log("API Response:", result);

        if (result.error) {
            document.getElementById('category-message').textContent = result.error;
            document.getElementById('category-message').style.display = 'block';
        } else if (result.id) {
            // Check that ID is valid before using it
            const categorySelect = document.getElementById('category');
            const newOption = document.createElement('option');
            newOption.value = result.id;
            newOption.textContent = categoryName;
            categorySelect.appendChild(newOption);
            categorySelect.value = result.id;
            closeModal();
        } else {
            console.error("Unexpected response format:", result);
        }
    };

</script>
</body>
</html>

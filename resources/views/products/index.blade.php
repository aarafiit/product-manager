<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Grocery Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        main {
            padding: 20px;
            padding-bottom: 60px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 250px;
            box-sizing: border-box;
        }

        .card img {
            width: 100%;
            border-radius: 4px;
            height: 150px; /* Adjust height as needed */
            object-fit: cover;
        }

        .card h3 {
            margin: 0 0 10px;
        }

        .card p {
            margin: 0 0 10px;
        }

        .card-actions {
            display: flex;
            justify-content: space-between;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #4CAF50;
        }

        .btn-delete {
            background-color: #f44336;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .btn-delete:hover {
            background-color: #e53935;
        }

        footer {
            background-color: white;
            color: black;
            text-align: center;
            padding: 10px;
            width: 100%;
            margin-top: 20px;
            position: relative;
        }

        /* Search form styling */
        .search-form {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
            margin-left: 130px;
            margin-right: 132px;
        }

        .search-form div {
            flex: 1 1 auto;
            min-width: 200px;
        }

        .search-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .search-form input,
        .search-form select {
            width: 75%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-form button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #45a049;
        }

        .search-form .sort-section {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-form .sort-section label {
            margin: 0;
        }
    </style>
</head>
<body>
<main>

    <div>

    <form method="GET" action="{{ route('products.search') }}" class="search-form">
        <div>
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="{{ request('name') }}">
        </div>

        <div>
            <label for="min_price">Min Price:</label>
            <input type="number" id="min_price" name="min_price" step="0.01" value="{{ request('min_price') }}">
        </div>
        <div>
            <label for="max_price">Max Price:</label>
            <input type="number" id="max_price" name="max_price" step="0.01" value="{{ request('max_price') }}">
        </div>
        <div>
            <label for="category">Category:</label>
            <select id="category" name="category_id">
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="sort-section">
            <div>
                <label for="sort_by">Sort By:</label>
                <select id="sort_by" name="sort_by">
                    <option value="">None</option>
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                </select>
            </div>
            <div>
                <label for="sort_order">Order:</label>
                <select id="sort_order" name="sort_order">
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>
            <button type="submit">Search</button>
        </div>
    </form>
        <div class="card-container" id="product-list">
            @foreach ($products as $product)
            <div class="card" data-name="{{ strtolower($product->name) }}">
                @if ($product->image)
                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">
                @else
                <img src="https://via.placeholder.com/250x150" alt="No Image">
                @endif
                <h3>{{ $product->name }}</h3>
                <p><strong>Category:</strong> {{ optional($product->category)->name }}</p>
                <p><strong>Description:</strong> {{ $product->description }}</p>
                <p><strong>Quantity:</strong> {{ $product->qty }}</p>
                <p><strong>Price:</strong> ${{ $product->price }}</p>
                <div class="card-actions">
                    <a href="{{ route('product.edit', ['product' => $product->id]) }}" class="btn btn-edit">Edit</a>
                    <form method="POST" action="{{ route('product.destroy', ['product' => $product->id]) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>


        <div style="padding: 10px;margin-left: 130px">
            @unless(Route::is('products.search'))
            <a href="{{ route('product.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Create Product</a>
            <a href="{{ route('products.export') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 5px;">Export Report</a>
            @endunless
        </div>
    </div>
</main>

<footer>
    <p>2024 &copy; BracIT All rights reserved.</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('name');
        const productList = document.getElementById('product-list');

        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.toLowerCase();
            const cards = productList.getElementsByClassName('card');

            for (let card of cards) {
                const productName = card.getAttribute('data-name');
                if (productName.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    });
</script>

</body>
</html>

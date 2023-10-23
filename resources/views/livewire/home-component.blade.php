<div class="px-10 mt-8 grid grid-cols-1 gap-4">
    @foreach ($products as $key => $category)
        <section class="mb-4 p-4 bg-gray-100 dark:bg-gray-900 rounded-md" open>
            <h2 class="text-white text-xl">{{ $categories->where('id', $key)->first()->name }}</h2>

            <div class="flex flex-wrap justify-start gap-4 mt-4">
                @foreach ($category as $product)
                    <a href="/products/{{ $product['id'] }}"
                        class="bg-white dark:bg-gray-900 shadow-2xl rounded-md w-56 p-2">
                        <img src="{{ "/storage/products/{$product['id']}/{$product['photo']}" }}"
                            alt="{{ "{$product['photo']} photo" }}" class="w-full max-h-40 object-contain rounded-md">

                        <div class="mt-2 text-white">{{ $product['name'] }}</div>
                        <div class="text-gray-300 text-sm -mt-1">
                            {{ $stores->where('id', $product['store_id'])->first()->name }}</div>
                        <div class="text-xl text-white">${{ $product['price'] }}</div>
                    </a>
                @endforeach
            </div>

        </section>
    @endforeach
</div>

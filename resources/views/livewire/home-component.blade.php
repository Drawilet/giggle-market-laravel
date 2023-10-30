<div class="px-10 mt-8 grid grid-cols-1 gap-4">
    @foreach ($products as $key => $category)
        <section class="mb-4 p-4 rounded-md" open>
            <h2 class="text-xl">{{ $categories->where('id', $key)->first()->name }}</h2>

            <div class="flex flex-wrap justify-start gap-4 mt-4">
                @foreach ($category as $product)
                    <a href="/products/{{ $product['id'] }}"
                        class="shadow-2xl rounded-md w-56 p-2">
                        <img src="{{ "/storage/products/{$product['id']}/{$product['photo']}" }}"
                            alt="{{ "{$product['photo']} photo" }}" class="w-full max-h-40 object-contain rounded-md">

                        <div class="mt-2 ">{{ $product['name'] }}</div>
                        <div class="text-sm -mt-1 opacity-80">
                            {{ $stores->where('id', $product['store_id'])->first()->name }}</div>
                        <div class="text-xl ">${{ $product['price'] }}</div>
                    </a>
                @endforeach
            </div>

        </section>
    @endforeach
</div>

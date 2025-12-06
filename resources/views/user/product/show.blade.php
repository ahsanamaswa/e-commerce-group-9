@extends('layouts.app')

@section('title', $product->name . ' - Tumbloo')

@section('content')
<div class="bg-tumbloo-dark min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8 flex items-center gap-2 text-sm text-tumbloo-gray">
            <a href="{{ route('home') }}" class="hover:text-tumbloo-white">Home</a>
            <span>›</span>
            <a href="{{ route('home.category', $product->category->slug) }}" class="hover:text-tumbloo-white">{{ $product->category->name }}</a>
            <span>›</span>
            <span class="text-tumbloo-white">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
            <!-- Product Images -->
            <div>
                <div class="bg-tumbloo-black rounded-lg overflow-hidden border border-tumbloo-accent mb-4">
                    <div id="mainImage" class="aspect-square">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-tumbloo-dark">
                                <svg class="w-32 h-32 text-tumbloo-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Thumbnails -->
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($product->images as $image)
                            <button onclick="changeImage('{{ asset('storage/' . $image->image) }}')" 
                                    class="aspect-square rounded-lg overflow-hidden border-2 border-tumbloo-accent hover:border-tumbloo-accent-light transition">
                                <img src="{{ asset('storage/' . $image->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <div class="mb-6">
                    <a href="#" class="inline-flex items-center gap-2 text-sm text-tumbloo-accent hover:text-tumbloo-accent-light mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ $product->store->name }}
                    </a>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-tumbloo-white mb-4">{{ $product->name }}</h1>
                    
                    <!-- Rating -->
                    @if($product->reviews->count() > 0)
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $product->reviews->avg('rating') ? 'text-yellow-400' : 'text-tumbloo-gray' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                <span class="text-tumbloo-white font-semibold ml-2">{{ number_format($product->reviews->avg('rating'), 1) }}</span>
                            </div>
                            <span class="text-tumbloo-gray">|</span>
                            <span class="text-tumbloo-gray">{{ $product->reviews->count() }} Ulasan</span>
                        </div>
                    @endif

                    <!-- Price -->
                    <div class="bg-tumbloo-black border border-tumbloo-accent rounded-lg p-6 mb-6">
                        <div class="text-3xl font-bold text-tumbloo-accent mb-2">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-tumbloo-gray">Stok: <span class="text-tumbloo-white font-semibold">{{ $product->stock }}</span></span>
                            <span class="text-tumbloo-gray">|</span>
                            <span class="text-tumbloo-gray">Kategori: <span class="text-tumbloo-accent">{{ $product->category->name }}</span></span>
                        </div>
                    </div>

                    <!-- Add to Cart -->
                    @if($product->stock > 0)
                        <button onclick="addToCart({{ $product->id }})" 
                                class="w-full bg-tumbloo-accent hover:bg-tumbloo-accent-light text-white font-semibold py-4 px-6 rounded-lg transition transform hover:scale-105 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Tambah ke Keranjang
                        </button>
                    @else
                        <button disabled class="w-full bg-tumbloo-gray text-tumbloo-dark font-semibold py-4 px-6 rounded-lg cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="bg-tumbloo-black rounded-lg border border-tumbloo-accent p-8 mb-12">
            <h2 class="text-2xl font-bold text-tumbloo-white mb-4">Deskripsi Produk</h2>
            <div class="text-tumbloo-gray leading-relaxed">
                {{ $product->about }}
            </div>
        </div>

        <!-- Reviews -->
        @if($product->reviews->count() > 0)
            <div class="bg-tumbloo-black rounded-lg border border-tumbloo-accent p-8 mb-12">
                <h2 class="text-2xl font-bold text-tumbloo-white mb-6">Ulasan Pembeli</h2>
                <div class="space-y-6">
                    @foreach($product->reviews as $review)
                        <div class="border-b border-tumbloo-accent pb-6 last:border-0">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-tumbloo-accent flex items-center justify-center text-white font-semibold">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="font-semibold text-tumbloo-white">{{ $review->user->name }}</span>
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-tumbloo-gray' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-tumbloo-gray">{{ $review->review }}</p>
                                    <span class="text-xs text-tumbloo-gray-dark mt-2 block">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-tumbloo-white mb-6">Produk Serupa</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('product.show', $related->id) }}" class="group">
                            <div class="bg-tumbloo-black rounded-lg overflow-hidden border border-tumbloo-accent hover:border-tumbloo-accent-light transition">
                                <div class="aspect-square overflow-hidden bg-tumbloo-dark">
                                    @if($related->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $related->images->first()->image) }}" 
                                             alt="{{ $related->name }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition">
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-tumbloo-white font-semibold mb-2 line-clamp-2">{{ $related->name }}</h3>
                                    <div class="text-tumbloo-accent font-bold">Rp {{ number_format($related->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function changeImage(src) {
    document.querySelector('#mainImage img').src = src;
}

function addToCart(productId) {
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message);
        }
    });
}
</script>
@endsection
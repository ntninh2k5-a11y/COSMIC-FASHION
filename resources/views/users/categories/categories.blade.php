@extends('layouts.app')

@section('title', 'Danh mục sản phẩm - Cosmic Fashion')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/categories.css') }}">
@endpush

@section('content')
<main class="py-5">
    <div class="container">

        <div class="mb-5 hieu-ung-bay-len">
            <h2 class="fw-bold mb-2 text-dark">Danh mục sản phẩm</h2>
            <p class="text-secondary">Khám phá tất cả các danh mục sản phẩm của chúng tôi</p>
        </div>

        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-md-4 col-sm-6 hieu-ung-bay-len">
                    <a href="{{ $category->routeLink }}" class="text-decoration-none">
                        <div class="khung-category">
                            <img src="{{ $category->image_url ? asset($category->image_url) : 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=600&auto=format&fit=crop&q=60' }}" 
                                 alt="{{ $category->name }}" 
                                 class="anh-category"
                                 style="width: 100%; height: 350px; object-fit: cover;">
                            
                            <div class="lop-phu-gradient">
                                <div class="noi-dung-category">
                                    <h4 class="text-white fw-bold mb-1">{{ $category->name }}</h4>
                                    <p class="text-white opacity-75 mb-0 small">{{ $category->description ?? 'Khám phá bộ sưu tập ' . $category->name }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        
    </div>
</main>
@endsection
@extends('admin.layouts.admin')

@section('title', 'Thêm mục Footer - Admin')
@section('page-title', 'THÊM MỤC FOOTER MỚI')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="neo-card">
            <h4 class="fw-bolder mb-1">Thêm mục mới</h4>
            <p class="text-muted mb-4" style="font-size:13px;">Tạo cột tiêu đề mới hoặc thêm liên kết vào cột có sẵn.</p>

            <form action="{{ route('admin.footer_menus.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="fw-bolder mb-2 text-dark" style="font-size:12px; letter-spacing:0.5px;">TÊN HIỂN THỊ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control neo-input" required
                               placeholder="Ví dụ: Hỗ trợ khách hàng" value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="fw-bolder mb-2 text-dark" style="font-size:12px; letter-spacing:0.5px;">THỨ TỰ HIỂN THỊ</label>
                        <input type="number" name="sort_order" class="form-control neo-input" value="{{ old('sort_order', 0) }}" min="0">
                        <div class="text-muted mt-1" style="font-size:11px;">Số nhỏ → hiển thị trước</div>
                    </div>

                    <div class="col-12">
                        <label class="fw-bolder mb-2 text-dark" style="font-size:12px; letter-spacing:0.5px;">THUỘC CỘT TIÊU ĐỀ (CHA)</label>
                        <select name="parent_id" class="form-select neo-input">
                            <option value="">— Tạo thành Cột tiêu đề mới —</option>
                            @foreach($footerColumns as $col)
                                <option value="{{ $col->id }}" {{ old('parent_id') == $col->id ? 'selected' : '' }}>
                                    {{ $col->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-muted mt-1" style="font-size:11px;">Để trống nếu đây là tiêu đề cột (Hỗ trợ, Về chúng tôi…). Chọn cột cha nếu đây là liên kết bên dưới.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bolder mb-2 text-dark" style="font-size:12px; letter-spacing:0.5px;">LOẠI LIÊN KẾT</label>
                        <select name="type" class="form-select neo-input">
                            <option value="default" {{ old('type') == 'default' ? 'selected' : '' }}>Mặc định (Text/Link)</option>
                            <option value="social" {{ old('type') == 'social' ? 'selected' : '' }}>Mạng xã hội (Nút tròn nhỏ)</option>
                            <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>Thanh toán (Nút vuông nhỏ)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bolder mb-2 text-dark" style="font-size:12px; letter-spacing:0.5px;">TRẠNG THÁI</label>
                        <select name="status" class="form-select neo-input" required>
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>✅ Hiển thị</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>❌ Đang ẩn</option>
                        </select>
                    </div>

                    <div class="col-12">

                        <label class="fw-bolder mb-2 text-dark" style="font-size:12px; letter-spacing:0.5px;">ĐƯỜNG DẪN (URL)</label>
                        <input type="text" name="url" class="form-control neo-input" value="{{ old('url', '#') }}"
                               id="url-field"
                               placeholder="https://... hoặc /duong-dan hoặc #">
                    </div>

                    <div class="col-12">
                        <label class="fw-bolder mb-2 text-dark d-block" style="font-size:12px; letter-spacing:0.5px;">KIỂU HIỂN THỊ TÊN</label>
                        <div class="d-flex gap-3">
                            <label class="footer-type-option" id="opt-link">
                                <input type="radio" name="is_static" value="0" {{ old('is_static','0') == '0' ? 'checked' : '' }} onchange="toggleUrlField(this)">
                                <div class="footer-type-box">
                                    <i class="bi bi-link-45deg fs-5 mb-1"></i>
                                    <div class="fw-bold" style="font-size:13px;">Liên kết (Link)</div>
                                    <div style="font-size:11px; color:#64748b; margin-top:2px;">Tên có thể click → đi tới URL</div>
                                </div>
                            </label>
                            <label class="footer-type-option" id="opt-static">
                                <input type="radio" name="is_static" value="1" {{ old('is_static') == '1' ? 'checked' : '' }} onchange="toggleUrlField(this)">
                                <div class="footer-type-box">
                                    <i class="bi bi-fonts fs-5 mb-1"></i>
                                    <div class="fw-bold" style="font-size:13px;">Văn bản tĩnh</div>
                                    <div style="font-size:11px; color:#64748b; margin-top:2px;">Chỉ hiển thị chữ, không click</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bolder mb-2 text-dark" style="font-size:12px; letter-spacing:0.5px;">ICON (Bootstrap Icons)</label>
                        <div class="input-group">
                            <span class="input-group-text neo-input" id="icon-preview" style="border-right:none; background:#f9fafb; min-width:42px; justify-content:center;">
                                <i class="bi bi-link-45deg text-muted"></i>
                            </span>
                            <input type="text" name="icon" class="form-control neo-input" id="icon-input"
                                   style="border-left:none;"
                                   placeholder="bi-house, bi-telephone, bi-instagram..." value="{{ old('icon') }}">
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px;">
                            Tham khảo: <a href="https://icons.getbootstrap.com" target="_blank" style="color:#FF6B6B;">icons.getbootstrap.com</a>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="fw-bolder mb-2 text-dark" style="font-size:12px; letter-spacing:0.5px;">MÔ TẢ NGẮN (chỉ hiển thị dưới tiêu đề cột)</label>
                        <textarea name="description" class="form-control neo-input" rows="2"
                                  placeholder="Ví dụ: Chúng tôi hỗ trợ 24/7 qua điện thoại và email...">{{ old('description') }}</textarea>
                        <div class="text-muted mt-1" style="font-size:11px;">Tùy chọn. Chỉ dùng cho cột tiêu đề, không dùng cho liên kết thường.</div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.footer_menus.index') }}" class="btn btn-outline-dark rounded-3 px-4 fw-bold" style="font-size:13px;">
                        ← Quay lại
                    </a>
                    <button type="submit" class="neo-btn border-0">
                        <i class="bi bi-save me-1"></i> LƯU THÔNG TIN
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Sidebar help --}}
    <div class="col-lg-4">
        <div class="neo-card mb-4" style="background:#f8fafc;">
            <h6 class="fw-bolder mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Hướng dẫn</h6>
            <div style="font-size:13px; line-height:1.8; color:#64748b;">
                <p><strong class="text-dark">Cột tiêu đề:</strong> Để trống mục "Thuộc cột cha". Đây sẽ là tiêu đề nhóm (vd: Hỗ trợ KH).</p>
                <p><strong class="text-dark">Liên kết:</strong> Chọn cột cha để gắn liên kết này vào bên dưới tiêu đề đó.</p>
                <p class="mb-0"><strong class="text-dark">Icon:</strong> Dùng tên class từ Bootstrap Icons, ví dụ <code>bi-telephone</code>, <code>bi-envelope</code>.</p>
            </div>
        </div>

        <div class="neo-card" style="background:#0f172a; color:#e2e8f0;">
            <h6 class="fw-bolder mb-3" style="color:#4ECDC4; font-size:11px; letter-spacing:1px; text-transform:uppercase;">
                <i class="bi bi-eye me-1"></i> Preview Icon phổ biến
            </h6>
            <div class="d-flex flex-wrap gap-2">
                @foreach(['bi-telephone','bi-envelope','bi-geo-alt','bi-instagram','bi-facebook','bi-tiktok','bi-truck','bi-arrow-repeat','bi-shield-check','bi-star','bi-heart','bi-question-circle'] as $ic)
                <span class="badge" style="background:#1e293b;color:#94a3b8;font-size:12px;cursor:pointer;border-radius:6px;padding:6px 10px;"
                      onclick="document.getElementById('icon-input').value='{{ $ic }}'; document.getElementById('icon-preview').innerHTML='<i class=\'bi {{ $ic }}\' style=\'color:#FF6B6B\'></i>';"
                      title="{{ $ic }}">
                    <i class="bi {{ $ic }}"></i>
                </span>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('icon-input').addEventListener('input', function() {
        const val = this.value.trim();
        const preview = document.getElementById('icon-preview');
        preview.innerHTML = val ? `<i class="bi ${val}" style="color:#FF6B6B;"></i>` : '<i class="bi bi-link-45deg text-muted"></i>';
    });

    function toggleUrlField(radio) {
        const urlField = document.getElementById('url-field');
        if (radio.value === '1') {
            urlField.style.opacity = '0.4';
            urlField.style.pointerEvents = 'none';
        } else {
            urlField.style.opacity = '1';
            urlField.style.pointerEvents = '';
        }
    }
    // Init
    const checked = document.querySelector('input[name="is_static"]:checked');
    if (checked) toggleUrlField(checked);
</script>
<style>
    .footer-type-option { cursor: pointer; }
    .footer-type-option input[type=radio] { display: none; }
    .footer-type-box {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 14px 24px; border: 2px solid #e5e7eb; border-radius: 12px;
        background: #f9fafb; text-align: center; transition: all 0.2s ease;
        min-width: 140px;
    }
    .footer-type-option input[type=radio]:checked + .footer-type-box {
        border-color: #FF6B6B; background: #fff5f5; color: #FF6B6B;
    }
    .footer-type-box:hover { border-color: #FF6B6B; }
</style>
@endpush
@endsection
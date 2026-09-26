@extends('users.profile.layout')
@section('title', 'Sổ Địa Chỉ - Cosmic Fashion')
@section('profile_content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt text-muted me-2"></i>Sổ địa chỉ</h5>
    <button class="btn btn-add-address" data-bs-toggle="modal" data-bs-target="#addressModal" onclick="resetAddressForm()">
        <i class="bi bi-plus-lg"></i> Thêm địa chỉ
    </button>
</div>

@if($addresses->count() > 0)
    <div class="address-list">
        @foreach($addresses as $addr)
        <div class="address-card {{ $addr->is_default ? 'is-default' : '' }}">
            <div class="address-card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="address-name">{{ $addr->receiver_name }}</span>
                            <span class="address-divider">|</span>
                            <span class="address-phone">{{ $addr->phone_number }}</span>
                            @if($addr->is_default)
                                <span class="address-default-badge">Mặc định</span>
                            @endif
                        </div>
                        <p class="address-text mb-1">{{ $addr->receiver_address }}</p>
                        @if($addr->note)
                            <p class="address-note mb-0"><i class="bi bi-chat-left-text me-1"></i>{{ $addr->note }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="address-card-actions">
                <button class="btn-addr-edit" onclick="editAddress({{ json_encode($addr) }})">
                    <i class="bi bi-pencil"></i> Sửa
                </button>
                @if(!$addr->is_default)
                    <form action="{{ route('user.addresses.setDefault', $addr->id) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-addr-default"><i class="bi bi-check-circle"></i> Đặt mặc định</button>
                    </form>
                @endif
                <form action="{{ route('user.addresses.destroy', $addr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa địa chỉ này?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-addr-delete"><i class="bi bi-trash"></i> Xóa</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-geo-alt" style="font-size: 3rem; color: #d1d5db;"></i>
        <p class="text-muted mt-3 mb-0">Bạn chưa có địa chỉ nào.</p>
        <p class="text-muted small">Thêm địa chỉ để đặt hàng nhanh hơn.</p>
    </div>
@endif

{{-- ADD/EDIT MODAL --}}
<div class="modal fade" id="addressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content address-modal-content">
            <div class="modal-header address-modal-header">
                <h6 class="modal-title fw-bold" id="addressModalLabel">Thêm địa chỉ mới</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addressForm" method="POST" action="{{ route('user.addresses.store') }}">
                @csrf
                <input type="hidden" name="_method" id="addressMethod" value="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" class="form-control address-input" name="receiver_name" id="addr_receiver_name" required placeholder="Nguyễn Văn A">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control address-input" name="phone_number" id="addr_phone_number" required placeholder="0912 345 678">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                            <select class="form-select address-input" name="province" id="addr_province" required>
                                <option value="">-- Chọn Tỉnh/TP --</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Quận/Huyện <span class="text-danger">*</span></label>
                            <select class="form-select address-input" name="district" id="addr_district" required disabled>
                                <option value="">-- Chọn Quận/Huyện --</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Phường/Xã <span class="text-danger">*</span></label>
                            <select class="form-select address-input" name="ward" id="addr_ward" required disabled>
                                <option value="">-- Chọn Phường/Xã --</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                        <input type="text" class="form-control address-input" name="street_address" id="addr_street_address" required placeholder="Số nhà, tên đường, ngõ/hẻm...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú</label>
                        <input type="text" class="form-control address-input" name="note" id="addr_note" placeholder="Ví dụ: Giao giờ hành chính">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_default" id="addr_is_default" value="1">
                        <label class="form-check-label" for="addr_is_default">Đặt làm địa chỉ mặc định</label>
                    </div>
                </div>
                <div class="modal-footer address-modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-save-address">Lưu địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const API_BASE = 'https://provinces.open-api.vn/api';

// Cache để không gọi API nhiều lần
let provincesData = [];

// Load danh sách tỉnh khi trang mở
document.addEventListener('DOMContentLoaded', function() {
    fetch(API_BASE + '/p/')
        .then(r => r.json())
        .then(data => {
            provincesData = data;
            const sel = document.getElementById('addr_province');
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.name;
                opt.dataset.code = p.code;
                opt.textContent = p.name;
                sel.appendChild(opt);
            });
        });
});

// Khi chọn Tỉnh → load Quận/Huyện
document.getElementById('addr_province').addEventListener('change', function() {
    const distSel = document.getElementById('addr_district');
    const wardSel = document.getElementById('addr_ward');
    distSel.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
    wardSel.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
    wardSel.disabled = true;

    const selected = this.options[this.selectedIndex];
    const code = selected.dataset.code;
    if (!code) { distSel.disabled = true; return; }

    fetch(API_BASE + '/p/' + code + '?depth=2')
        .then(r => r.json())
        .then(data => {
            data.districts.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.name;
                opt.dataset.code = d.code;
                opt.textContent = d.name;
                distSel.appendChild(opt);
            });
            distSel.disabled = false;
        });
});

// Khi chọn Quận → load Phường/Xã
document.getElementById('addr_district').addEventListener('change', function() {
    const wardSel = document.getElementById('addr_ward');
    wardSel.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

    const selected = this.options[this.selectedIndex];
    const code = selected.dataset.code;
    if (!code) { wardSel.disabled = true; return; }

    fetch(API_BASE + '/d/' + code + '?depth=2')
        .then(r => r.json())
        .then(data => {
            data.wards.forEach(w => {
                const opt = document.createElement('option');
                opt.value = w.name;
                opt.dataset.code = w.code;
                opt.textContent = w.name;
                wardSel.appendChild(opt);
            });
            wardSel.disabled = false;
        });
});

function resetAddressForm() {
    document.getElementById('addressModalLabel').textContent = 'Thêm địa chỉ mới';
    document.getElementById('addressForm').action = '{{ route("user.addresses.store") }}';
    document.getElementById('addressMethod').value = 'POST';
    document.getElementById('addr_receiver_name').value = '';
    document.getElementById('addr_phone_number').value = '';
    document.getElementById('addr_province').value = '';
    document.getElementById('addr_district').innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
    document.getElementById('addr_district').disabled = true;
    document.getElementById('addr_ward').innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
    document.getElementById('addr_ward').disabled = true;
    document.getElementById('addr_street_address').value = '';
    document.getElementById('addr_note').value = '';
    document.getElementById('addr_is_default').checked = false;
}

function editAddress(addr) {
    document.getElementById('addressModalLabel').textContent = 'Cập nhật địa chỉ';
    document.getElementById('addressForm').action = '/dia-chi/' + addr.id;
    document.getElementById('addressMethod').value = 'PUT';
    document.getElementById('addr_receiver_name').value = addr.receiver_name;
    document.getElementById('addr_phone_number').value = addr.phone_number;
    document.getElementById('addr_street_address').value = addr.street_address || '';
    document.getElementById('addr_note').value = addr.note || '';
    document.getElementById('addr_is_default').checked = addr.is_default;

    // Set province and cascade
    const provSel = document.getElementById('addr_province');
    if (addr.province) {
        setSelectValue(provSel, addr.province);
        // Trigger district load
        const provOpt = provSel.options[provSel.selectedIndex];
        if (provOpt && provOpt.dataset.code) {
            fetch(API_BASE + '/p/' + provOpt.dataset.code + '?depth=2')
                .then(r => r.json())
                .then(data => {
                    const distSel = document.getElementById('addr_district');
                    distSel.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                    data.districts.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.name;
                        opt.dataset.code = d.code;
                        opt.textContent = d.name;
                        distSel.appendChild(opt);
                    });
                    distSel.disabled = false;
                    if (addr.district) {
                        setSelectValue(distSel, addr.district);
                        // Trigger ward load
                        const distOpt = distSel.options[distSel.selectedIndex];
                        if (distOpt && distOpt.dataset.code) {
                            fetch(API_BASE + '/d/' + distOpt.dataset.code + '?depth=2')
                                .then(r => r.json())
                                .then(data2 => {
                                    const wardSel = document.getElementById('addr_ward');
                                    wardSel.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                                    data2.wards.forEach(w => {
                                        const opt = document.createElement('option');
                                        opt.value = w.name;
                                        opt.dataset.code = w.code;
                                        opt.textContent = w.name;
                                        wardSel.appendChild(opt);
                                    });
                                    wardSel.disabled = false;
                                    if (addr.ward) setSelectValue(wardSel, addr.ward);
                                });
                        }
                    }
                });
        }
    }

    new bootstrap.Modal(document.getElementById('addressModal')).show();
}

function setSelectValue(selectEl, value) {
    for (let i = 0; i < selectEl.options.length; i++) {
        if (selectEl.options[i].value === value) {
            selectEl.selectedIndex = i;
            return;
        }
    }
}
</script>
@endpush

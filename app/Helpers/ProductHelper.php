<?php

namespace App\Helpers;

/**
 * Lớp tiện ích chứa dữ liệu dùng chung cho Size & Màu sắc.
 */
class ProductHelper
{
    /**
     * Bảng chuyển đổi mã HEX → tên tiếng Việt.
     * Dùng chung ở Cart, Order, ProductDetail, Admin...
     */
    public static function colorNames(): array
    {
        return [
            // 15 màu chính (khớp với Admin)
            '#000000' => 'Đen',
            '#FFFFFF' => 'Trắng',
            '#001F3F' => 'Xanh Navy',
            '#F5F5DC' => 'Be',
            '#808080' => 'Xám',
            '#DC2626' => 'Đỏ',
            '#EC4899' => 'Hồng',
            '#2563EB' => 'Xanh Dương',
            '#16A34A' => 'Xanh Lá',
            '#EAB308' => 'Vàng',
            '#EA580C' => 'Cam',
            '#7C3AED' => 'Tím',
            '#92400E' => 'Nâu',
            '#FFFDD0' => 'Kem',
            '#556B2F' => 'Xanh Rêu',

            // Các mã HEX cũ (tương thích ngược)
            '#000080' => 'Xanh Navy',
            '#ADD8E6' => 'Xanh nhạt',
            '#2F4F4F' => 'Xám đậm',
            '#8B4513' => 'Nâu',
            '#FF0000' => 'Đỏ',
            '#008000' => 'Xanh lá',
            '#D3D3D3' => 'Xám nhạt',
            '#A9A9A9' => 'Xám',
            '#4169E1' => 'Xanh dương',
            '#00008B' => 'Xanh đậm',
            '#FFB6C1' => 'Hồng',
            '#FFC0CB' => 'Hồng',
            '#D2B48C' => 'Be',
            '#FFDAB9' => 'Be',
            '#C0C0C0' => 'Bạc',
        ];
    }
}

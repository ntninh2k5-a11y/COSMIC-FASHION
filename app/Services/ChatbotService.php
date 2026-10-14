<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ChatbotService
{
    // Đường dẫn kết nối tới Ollama
    protected string $ollamaUrl = 'http://127.0.0.1:11434/api/chat';
    protected string $model = 'qwen2.5:7b';

    /**
     * Hàm chính: Tiếp nhận câu hỏi và trả về câu trả lời của AI
     */
    public function generateReply(string $userMessage): array
    {
        try {
            // BƯỚC 1: Lấy dữ liệu CSDL (Danh mục, Sản phẩm, Biến thể) và ghép thành Prompt
            $prompt = $this->buildPrompt($userMessage);

            // BƯỚC 2: Gửi Prompt sang mô hình Ollama
            $response = Http::timeout(60)->post($this->ollamaUrl, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'stream' => false,
                'options' => [
                    'num_predict' => 500, // Đủ dung lượng để AI viết trọn vẹn câu và link không bị ngắt quãng
                ]
            ]);

            // BƯỚC 3: Trả kết quả về cho Controller
            if ($response->successful()) {
                $reply = $response->json('message.content') ?? 'Xin lỗi bạn, hiện tại tôi chưa tìm thấy thông tin phù hợp.';
                return [
                    'success' => true,
                    'reply' => trim($reply),
                ];
            }

            return [
                'success' => false,
                'reply' => 'Hệ thống AI đang phản hồi chậm, bạn vui lòng thử lại sau giây lát nhé!',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'reply' => 'Không thể kết nối đến Ollama. Vui lòng kiểm tra lại dịch vụ Ollama trên máy tính.',
            ];
        }
    }

    /**
     * BƯỚC 1 CHI TIẾT: Lấy thông tin từ CSDL và xây dựng Prompt hướng dẫn AI
     */
    protected function buildPrompt(string $userMessage): string
    {
        // 1. Lấy danh sách thể loại / danh mục của shop
        $categories = Category::where('status', 1)->pluck('name')->implode(', ');

        // 2. Lấy 3 sản phẩm phù hợp nhất (để câu trả lời cô đọng và không bị quá dài)
        $products = Product::with(['category', 'variants'])
            ->where('status', 1)
            ->where('name', 'LIKE', "%{$userMessage}%")
            ->take(3)
            ->get();

        if ($products->isEmpty()) {
            $products = Product::with(['category', 'variants'])
                ->where('status', 1)
                ->latest()
                ->take(3)
                ->get();
        }

        // 3. Gom thông tin chi tiết từng sản phẩm (Tên, Giá, Màu sắc, Size, Link chi tiết)
        $colorNames = \App\Helpers\ProductHelper::colorNames();
        $productList = [];
        foreach ($products as $p) {
            $price = number_format($p->sale_price ?: $p->price, 0, ',', '.') . ' VNĐ';
            $colors = $p->variants->pluck('color')->map(fn($c) => $colorNames[strtoupper($c)] ?? $c)->filter()->unique()->implode(', ') ?: 'Tiêu chuẩn';
            $sizes = $p->variants->pluck('size')->filter()->unique()->implode(', ') ?: 'Freesize';
            $link = url('/chi-tiet-san-pham/' . $p->id);
            $categoryName = $p->category->name ?? 'Thời trang';

            $productList[] = "- Tên: {$p->name} | Thể loại: {$categoryName} | Giá: {$price} | Màu: {$colors} | Size: {$sizes} | Link: {$link}";
        }
        $productsText = implode("\n", $productList);

        // 4. Ghép toàn bộ vào Prompt tự nhiên, rõ ràng
        return <<<PROMPT
Bạn là nhân viên tư vấn bán hàng của shop thời trang Cosmic Fashion.
Nhiệm vụ của bạn là tư vấn thân thiện, nhiệt tình cho khách hàng dựa vào dữ liệu có thật của cửa hàng dưới đây:

DANH MỤC SẢN PHẨM CỦA SHOP:
{$categories}

DANH SÁCH SẢN PHẨM & BIẾN THỂ (GIÁ, MÀU, SIZE):
{$productsText}

CÂU HỎI CỦA KHÁCH:
"{$userMessage}"

YÊU CẦU TƯ VẤN:
1. Xưng hô 'em/mình' với khách một cách lịch sự, nhã nhặn.
2. Giới thiệu tối đa 2 đến 3 sản phẩm trên.
3. Mỗi sản phẩm nêu ngắn gọn: Tên sản phẩm, Giá bán, Màu sắc, Size và đính kèm link dạng Markdown hoàn chỉnh: [Xem chi tiết tại đây](đường_dẫn_link).
4. Phải viết trọn vẹn câu, tuyệt đối không dừng dở dang khi chưa đóng ngoặc link.
PROMPT;
    }
}

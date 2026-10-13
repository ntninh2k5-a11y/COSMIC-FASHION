<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * Tên và Gợi ý câu hỏi của Chatbot
     */
    public const BOT_NAME = '2T&C AI';
    public const BOT_PLACEHOLDER = 'Bạn cần tìm sản phẩm gì?...';

    /**
     * Cấu hình kết nối Ollama AI cục bộ
     */
    protected string $ollamaBaseUrl = 'http://127.0.0.1:11434';
    protected string $model = 'qwen2.5:7b';
    protected int $timeout = 120;
    protected int $numPredict = 350;
    protected int $maxHistory = 8;

    /**
     * Xử lý câu hỏi của người dùng và gọi Ollama để sinh câu trả lời
     */
    public function generateReply(string $userMessage, array $history = []): array
    {
        try {
            // 1. Lấy dữ liệu danh mục & sản phẩm từ CSDL theo câu hỏi của khách
            $contextData = $this->buildDatabaseContext($userMessage);

            // 2. Ghép dữ liệu CSDL vào Prompt hướng dẫn AI
            $systemPrompt = $this->buildSystemPrompt($contextData);

            // 3. Chuẩn bị ngữ cảnh hội thoại (System prompt + Lịch sử gần nhất + Câu hỏi mới)
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
            ];

            $recentHistory = array_slice($history, -$this->maxHistory);
            foreach ($recentHistory as $msg) {
                if (isset($msg['role'], $msg['content']) && in_array($msg['role'], ['user', 'assistant'])) {
                    $messages[] = [
                        'role' => $msg['role'],
                        'content' => (string) $msg['content'],
                    ];
                }
            }

            $messages[] = ['role' => 'user', 'content' => $userMessage];

            // 4. Gửi yêu cầu tới máy chủ Ollama
            $ollamaUrl = rtrim($this->ollamaBaseUrl, '/') . '/api/chat';
            $response = Http::timeout($this->timeout)->post($ollamaUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'stream' => false,
                'options' => [
                    'temperature' => 0.7,
                    'top_p' => 0.9,
                    'num_predict' => $this->numPredict,
                ],
            ]);

            if ($response->successful()) {
                $reply = $response->json('message.content') ?? 'Xin lỗi bạn, tôi chưa hiểu rõ câu hỏi. Bạn có thể mô tả chi tiết hơn không?';

                return [
                    'success' => true,
                    'reply' => trim($reply),
                    'products' => $contextData['suggested_products_preview'] ?? [],
                ];
            }

            Log::error('Ollama API error response: ' . $response->body());
            return [
                'success' => false,
                'reply' => 'Hệ thống AI đang phản hồi chậm hoặc gặp sự cố kết nối. Bạn vui lòng thử lại sau giây lát nhé!',
                'products' => [],
            ];
        } catch (\Throwable $e) {
            Log::error('ChatbotService Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'reply' => 'Rất tiếc, hiện tại tôi không thể kết nối tới máy chủ AI Ollama. Vui lòng kiểm tra lại dịch vụ Ollama trên máy tính của bạn.',
                'products' => [],
            ];
        }
    }

    /**
     * Truy vấn CSDL (Category, Product, ProductVariant) phù hợp với câu hỏi
     */
    protected function buildDatabaseContext(string $userMessage): array
    {
        // Danh sách danh mục trong shop
        $categories = Category::where('status', 1)->pluck('name')->implode(', ');

        // Khởi tạo truy vấn sản phẩm
        $query = Product::with(['category', 'variants'])->where('status', 1);

        // Nếu khách hỏi về mức giá (ví dụ: "3,5 tr", "500k") -> lọc theo khoảng giá
        $targetPrice = $this->extractPrice($userMessage);
        if ($targetPrice !== null) {
            $minPrice = $targetPrice * 0.6;
            $maxPrice = $targetPrice * 1.5;
            $query->whereBetween('price', [$minPrice, $maxPrice])
                  ->orderByRaw("ABS(price - ?) ASC", [$targetPrice]);
        } else {
            // Nếu có từ khóa sản phẩm (ví dụ: "áo thun", "sơ mi", "váy") -> tìm theo tên
            $keywords = $this->extractKeywords($userMessage);
            if (!empty($keywords)) {
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $kw) {
                        $q->orWhere('name', 'LIKE', "%{$kw}%");
                    }
                });
            }
        }

        // Lấy 6 sản phẩm phù hợp nhất
        $products = $query->take(6)->get();

        // Nếu không có sản phẩm khớp từ khóa, lấy các sản phẩm nổi bật/mới nhất
        if ($products->isEmpty()) {
            $products = Product::with(['category', 'variants'])
                ->where('status', 1)
                ->orderByDesc('is_featured')
                ->latest()
                ->take(6)
                ->get();
        }

        // Chuyển dữ liệu sản phẩm thành văn bản mô tả cho AI
        $productsText = [];
        $previewProducts = [];

        foreach ($products as $p) {
            $priceVnd = number_format($p->sale_price ?: $p->price, 0, ',', '.') . ' VNĐ';

            // Lấy màu sắc và size từ variants (tên màu được Model ProductVariant tự chuyển đổi)
            $variantsStr = 'Chưa có phân loại';
            if ($p->variants && $p->variants->isNotEmpty()) {
                $colors = $p->variants->pluck('color_name')->filter()->unique()->implode(', ');
                $sizes = $p->variants->pluck('size')->filter()->unique()->implode(', ');
                $totalStock = $p->variants->sum('stock_quantity');
                $variantsStr = "Màu: [{$colors}] | Size: [{$sizes}] | Tồn kho: {$totalStock}";
            }

            $productUrl = url('/chi-tiet-san-pham/' . $p->id);

            $pText = "- ID: {$p->id} | Tên: {$p->name} | Danh mục: " . ($p->category->name ?? 'Khác');
            $pText .= " | Giá: {$priceVnd}";
            $pText .= " | Biến thể: {$variantsStr}";
            $pText .= " | Link: {$productUrl}";

            $productsText[] = $pText;

            $previewProducts[] = [
                'id' => $p->id,
                'name' => $p->name,
                'price' => (float) ($p->sale_price ?: $p->price),
                'price_formatted' => $priceVnd,
                'image_url' => $p->image_url ? asset($p->image_url) : null,
                'url' => $productUrl,
            ];
        }

        return [
            'categories' => $categories,
            'products_context' => implode("\n", $productsText),
            'suggested_products_preview' => array_slice($previewProducts, 0, 4),
        ];
    }

    /**
     * Ghép prompt hoàn chỉnh gửi cho AI
     */
    protected function buildSystemPrompt(array $contextData): string
    {
        $categories = $contextData['categories'];
        $productsContext = $contextData['products_context'];

        return <<<EOT
Bạn là chuyên viên tư vấn AI của cửa hàng thời trang Cosmic Fashion (thương hiệu 2T&C AI).
Nhiệm vụ của bạn là tư vấn nhiệt tình, lịch sự, khéo léo theo phong cách chuyên nghiệp.
Luôn dựa vào thông tin sản phẩm, phân loại (màu sắc, size, số lượng còn trong kho) và danh mục của cửa hàng được cung cấp bên dưới để tư vấn chính xác.

=== DANH MỤC SẢN PHẨM CỬA HÀNG ===
{$categories}

=== DỮ LIỆU SẢN PHẨM & BIẾN THỂ TRONG KHO ===
{$productsContext}

=== NGUYÊN TẮC TƯ VẤN ===
1. Hãy xưng hô 'mình/em' và gọi khách là 'bạn/anh/chị' một cách thân thiện, chu đáo.
2. ƯU TIÊN giới thiệu các sản phẩm CÓ THỰC trong danh sách kho bên trên. Nếu khách hỏi sản phẩm không có, hãy giải thích khéo léo và gợi ý mẫu tương tự đang có sẵn.
3. Khi giới thiệu bất kỳ sản phẩm nào, hãy cung cấp rõ ràng:
   - Tên sản phẩm chính xác.
   - Giá bán hiện tại.
   - Các màu sắc và size còn hàng (dựa trên biến thể).
   - Đính kèm đường dẫn xem chi tiết dạng Markdown: [Xem chi tiết tại đây](đường_dẫn_của_sản_phẩm)
4. Tư vấn tự nhiên, ngắn gọn, có cấu trúc dễ đọc, gạch đầu dòng hợp lý, không viết dài dòng lê thê.
EOT;
    }

    /**
     * Nhận diện số tiền nếu khách hàng hỏi về giá (ví dụ: "3,5 tr", "500k", "3tr5")
     */
    protected function extractPrice(string $message): ?float
    {
        $msg = strtolower($message);

        // "3tr5", "1tr2"
        if (preg_match('/(\d+)tr(\d+)/i', $msg, $m)) {
            return ((float) $m[1] * 1000000) + ((float) $m[2] * 100000);
        }

        // "3.5 tr", "3,5 triệu", "3 tr"
        if (preg_match('/([\d\.,]+)\s*(?:triệu|trieu|tr)\b/iu', $msg, $m)) {
            return (float) str_replace(',', '.', $m[1]) * 1000000;
        }

        // "500k", "300 nghìn", "200 ngàn"
        if (preg_match('/([\d\.,]+)\s*(?:k|nghìn|nghin|ngàn|ngan)\b/iu', $msg, $m)) {
            return (float) str_replace(',', '.', $m[1]) * 1000;
        }

        // Số tiền dạng số lớn: 3500000, 300.000
        if (preg_match('/\b(\d{1,3}(?:\.\d{3})+|\d{5,})\s*(?:đ|vnd|đồng)?\b/iu', $msg, $m)) {
            return (float) str_replace(['.', ','], '', $m[1]);
        }

        return null;
    }

    /**
     * Tách các từ khóa tìm kiếm từ câu hỏi
     */
    protected function extractKeywords(string $message): array
    {
        $stopWords = [
            'bạn', 'ban', 'tôi', 'toi', 'mình', 'minh', 'tư', 'vấn', 'tu', 'van',
            'cho', 'hỏi', 'hoi', 'có', 'co', 'không', 'khong', 'giúp', 'giup',
            'với', 'voi', 'shop', 'mua', 'cần', 'can', 'tìm', 'tim', 'một', 'mot',
            'sản', 'phẩm', 'san', 'pham', 'nào', 'nao', 'gì', 'gi', 'được', 'duoc',
            'ạ', 'nhé', 'nhe', 'nha', 'đi', 'di', 'ơi', 'oi', 'giá', 'gia'
        ];

        $words = preg_split('/[\s,\.\?!;:]+/u', mb_strtolower($message), -1, PREG_SPLIT_NO_EMPTY);
        $filtered = [];

        foreach ($words as $word) {
            $word = trim($word);
            if (mb_strlen($word) >= 2 && !in_array($word, $stopWords)) {
                $filtered[] = $word;
            }
        }

        return array_values(array_unique($filtered));
    }
}

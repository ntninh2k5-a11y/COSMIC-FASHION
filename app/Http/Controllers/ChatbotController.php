<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected ChatbotService $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Nhận câu hỏi từ giao diện và trả lời qua ChatbotService
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message', ''));
        if (empty($message)) {
            return response()->json(['success' => false, 'reply' => 'Vui lòng nhập nội dung tin nhắn.']);
        }

        $result = $this->chatbotService->generateReply($message);

        return response()->json($result);
    }

    /**
     * Xóa lịch sử (client quản lý qua localStorage)
     */
    public function clearHistory(): JsonResponse
    {
        return response()->json(['success' => true]);
    }
}

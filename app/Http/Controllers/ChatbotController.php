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
     * Gửi tin nhắn đến Chatbot và nhận phản hồi
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array|max:20',
            'history.*.role' => 'required_with:history|string|in:user,assistant',
            'history.*.content' => 'required_with:history|string',
        ]);

        $userMessage = trim($request->input('message'));
        $history = $request->input('history', []);

        $result = $this->chatbotService->generateReply($userMessage, $history);

        return response()->json($result);
    }

    /**
     * Xóa lịch sử phiên chat (nếu có lưu session)
     */
    public function clearHistory(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lịch sử đoạn chat đã được làm mới thành công.',
        ]);
    }
}

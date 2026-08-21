<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\UserDetail;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function index(Request $request)
    {
        // Get or create the active conversation
        $conversation = AiConversation::latest()->first();
        if (!$conversation) {
            $conversation = AiConversation::create(['title' => 'CRM Executive Copilot']);
            
            // Seed a welcome message
            AiMessage::create([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'content' => "Hello! 👋 I am your **CRM Pro AI Copilot**.\n\nI have full, real-time access to your live database across **Clients, Deals Pipeline, Invoices, and Leads**.\n\nHow can I assist your sales and revenue operations today?",
            ]);
        }

        $messages = $conversation->messages;

        // CRM Stats Snapshot for right sidebar
        $totalRevenue = Invoice::where('status', 'Paid')->sum('amount');
        $openDealsSum = Deal::whereNotIn('stage', ['won', 'lost'])->sum('amount');
        $pendingInvoicesCount = Invoice::where('status', 'Pending')->count();
        $totalClients = Client::count();
        $totalLeads = UserDetail::count();

        return view('ai.assistant', compact(
            'conversation',
            'messages',
            'totalRevenue',
            'openDealsSum',
            'pendingInvoicesCount',
            'totalClients',
            'totalLeads'
        ));
    }

    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'conversation_id' => 'nullable|exists:ai_conversations,id',
        ]);

        $conversation = null;
        if ($request->conversation_id) {
            $conversation = AiConversation::find($request->conversation_id);
        }
        if (!$conversation) {
            $conversation = AiConversation::create(['title' => substr($request->question, 0, 40)]);
        }

        // 1. Save User Message
        AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $request->question,
        ]);

        // 2. Fetch history
        $history = $conversation->messages()->take(10)->get()->map(fn($m) => [
            'role' => $m->role,
            'content' => $m->content,
        ])->toArray();

        // 3. Ask Gemini with live CRM context
        $aiResponse = $this->gemini->askCrmCopilot($request->question, $history);

        // 4. Save AI Reply
        $assistantMsg = AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $aiResponse,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'message' => $aiResponse,
                'html' => $this->markdownToHtml($aiResponse),
            ]);
        }

        return redirect()->route('ai.index');
    }

    public function clearHistory()
    {
        AiMessage::truncate();
        AiConversation::truncate();

        return redirect()->route('ai.index')->with('success', 'AI Conversation history cleared.');
    }

    protected function markdownToHtml(string $md): string
    {
        // Simple markdown formatter for bold, headers, code, bullet points
        $html = htmlspecialchars($md);
        $html = preg_replace('/### (.*?)\n/', '<h3 style="font-size: 16px; font-weight: 700; margin: 12px 0 6px;">$1</h3>', $html);
        $html = preg_replace('/#### (.*?)\n/', '<h4 style="font-size: 14px; font-weight: 700; margin: 10px 0 4px;">$1</h4>', $html);
        $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);
        $html = preg_replace('/`(.*?)`/', '<code style="background: rgba(99,102,241,0.1); padding: 2px 6px; border-radius: 4px; font-family: monospace; font-size: 12.5px;">$1</code>', $html);
        $html = nl2br($html);
        return $html;
    }
}
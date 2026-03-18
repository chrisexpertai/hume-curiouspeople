<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ChatHistory;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Throwable;
use App\Models\ChatGptMessage;
use Illuminate\Support\Facades\Broadcast; 

class ChatController extends Controller
{ 
    public function index()
    {
        $user = Auth::user();
        $chatHistories = $user->chatHistories()->get();

        return view('user.chat.index', compact('user', 'chatHistories'));
    }

    
    public function chatPage($id)
    {
        $user = Auth::user();
        $chatHistory = ChatHistory::findOrFail($id);
        $messages = $chatHistory->messages;
        $chatHistories = $user->chatHistories()->get();
    
        // Retrieve ChatGPT messages for the specific chat history
        $gptMessages = ChatGptMessage::where('chat_history_id', $chatHistory->id)->get();
    
        \Log::info('Retrieved Messages: ' . json_encode($messages));
    
        return view('user.chat.chatbox', compact('user', 'chatHistories', 'chatHistory', 'messages', 'gptMessages'));
    }
    


    public function createChat()
    {
        $user = Auth::user();
        $chatHistory = ChatHistory::create(['user_id' => $user->id, 'content' => 'New Chat']);

        return redirect()->route('user.chat.page', ['id' => $chatHistory->id]);
    }


public function updateChatName(Request $request, $id)
{
    $this->validate($request, [
        'content' => 'required|string',
    ]);

    $chatHistory = ChatHistory::findOrFail($id);
    $chatHistory->update(['content' => $request->input('content')]);

    return redirect()->back()->with('success', 'Chat name updated successfully.');
}public function sendMessage($user, $chatHistory, $userMessageContent)
{
    // Save user message
    return $user->messages()->create([
        'content' => $userMessageContent,
        'chat_history_id' => $chatHistory->id,
        'is_user' => true,
    ]);
}

public function createGptMessage($user, $chatHistory, $userMessageContent, $apiKey, $gptVersion)
{
    // GPT response
    $gptResponse = Http::withHeaders([
        "Content-Type" => "application/json",
        "Authorization" => "Bearer " . $apiKey
    ])->post('https://api.openai.com/v1/chat/completions', [
        "model" => $gptVersion,
        "messages" => [
            [
                "role" => "user",
                "content" => $userMessageContent,
            ]
        ],
        "temperature" => 0,
        "max_tokens" => 2048
    ])->json();

    // Save GPT response
    return ChatGptMessage::create([
        'user_id' => $user->id,
        'content' => $gptResponse['choices'][0]['message']['content'],
        'chat_history_id' => $chatHistory->id,
    ]);
}

public function __invoke(Request $request): string
{
    try {
        // Retrieve API key and GPT version from settings
        $setting = Setting::first();
        $apiKey = $setting->admin_api_key;
        $gptVersion = $setting->gpt_version;

        // User message
        $userMessageContent = $request->post('content');
        $user = Auth::user();
        $chatHistory = ChatHistory::findOrFail($request->post('chat_history_id'));

        // Use a database transaction to ensure atomicity
        return \DB::transaction(function () use ($user, $chatHistory, $userMessageContent, $apiKey, $gptVersion) {
            // Save user message
            $userMessage = $this->sendMessage($user, $chatHistory, $userMessageContent);

            // Save GPT response
            $gptMessage = $this->createGptMessage($user, $chatHistory, $userMessageContent, $apiKey, $gptVersion);

            // Return GPT message content
            return $gptMessage->content;
        });
    } catch (Throwable $e) {
        return "Chat GPT Limit Reached. This means too many people have used this demo this month and hit the FREE limit available. You will need to wait, sorry about that.";
    }
}
public function fetchChatHistory(Request $request)
{
    try {
        $chatHistoryId = $request->get('chat_history_id');
        // You can add additional validation or checks as needed

        // Fetch messages associated with the specified chat history
        $chatHistoryMessages = Message::where('chat_history_id', $chatHistoryId)->get();

        // You may also want to include additional information like user names, etc.
        // $chatHistoryMessages = $this->formatChatHistory($chatHistoryMessages);

        return response()->json(['messages' => $chatHistoryMessages]);
    } catch (\Throwable $e) {
        return response()->json(['error' => 'Failed to fetch chat history.']);
    }
}


 
 
    public function destroyChat($id)
    {
        try {
            $chatHistory = ChatHistory::findOrFail($id);

            // Check if the authenticated user owns the chat history
            if ($chatHistory->user_id != auth()->id()) {
                return redirect()->back()->with('error', 'You are not authorized to delete this chat.');
            }

            // Delete the chat history and its associated messages
            $chatHistory->messages()->delete();
            $chatHistory->delete();

            return redirect()->route('user.chat.index')->with('success', 'Chat deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('user.create.chat')->with('success', 'Deleted and Created A New Chat.');
        }
    }

     
    
    
    public function chatSettings()
    {
        $availableGPTs = Config::get('chat.available_gpts');

        return view('admin.chat', compact('availableGPTs'));
    }
}
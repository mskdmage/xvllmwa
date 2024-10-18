try {
    
    function scrollToBottom() {
        var chatHistory = document.getElementById('chat-history');
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    window.onload = function() {
        scrollToBottom();
    }
    
} catch {
    
}
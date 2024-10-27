try {
    
    function scrollToBottom() {
        var chatHistory = document.getElementById('chat-history');
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    window.onload = function() {
        scrollToBottom();
    }
    
} catch {

    console.error('Error al tratar de alcanzar el fin de la historia de chat:', error);    

}
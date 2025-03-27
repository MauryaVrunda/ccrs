<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div id="chat-box">
    <div id="messages"></div>
    <textarea id="message" placeholder="Type a message..."></textarea>
    <button onclick="sendMessage()">Send</button>
</div>
<script src="chat.js"></script>
<style>
    #chat-box {
    width: 300px;
    height: 400px;
    border: 1px solid #ccc;
    overflow-y: scroll;
    padding: 10px;
}
#messages {
    height: 300px;
    overflow-y: auto;
}
textarea {
    width: 100%;
    height: 50px;
}
</style>
</body>
</html>
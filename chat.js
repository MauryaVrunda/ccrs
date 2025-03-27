function sendMessage() {
    let message = document.getElementById("message").value.trim(); // Trim spaces
    let receiver_id = 2; // Change dynamically

    if (message === "") {
        alert("Message cannot be empty!");
        return;
    }

    fetch("save_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `message=${encodeURIComponent(message)}&receiver_id=${receiver_id}`
    })
    .then(response => response.text())
    .then(data => {
        console.log("Server Response:", data); // Debugging
        document.getElementById("message").value = ""; // Clear input
        loadMessages(); // Refresh chat
    })
    .catch(error => console.error("Error:", error));
}

function loadMessages() {
    let receiver_id = 2; // Change dynamically

    fetch("get_messages.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `receiver_id=${receiver_id}`
    })
    .then(response => response.json())
    .then(messages => {
        let chatBox = document.getElementById("messages");
        chatBox.innerHTML = ""; // Clear existing messages

        messages.forEach(msg => {
            let msgElement = document.createElement("p");
            msgElement.textContent = msg.message;
            chatBox.appendChild(msgElement);
        });
    })
    .catch(error => console.error("Error loading messages:", error));
}

// Auto-refresh messages every 2 seconds
setInterval(loadMessages, 2000);
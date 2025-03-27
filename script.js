// SEARCH FUNCTIONALITY
function performSearch() {
    let query = document.getElementById("search-box").value;
    if (query) {
        alert("Searching for: " + query);
    } else {
        alert("Please enter a search query.");
    }
}

// LANGUAGE CHANGE FUNCTIONALITY
function changeLanguage(lang) {
    let govText = document.getElementById("gov-text");
    let welcomeText = document.getElementById("welcome-text");
    let descriptionText = document.getElementById("description-text");
    let languageButton = document.getElementById("language-button");

    if (lang === 'gu') {
        govText.innerText = "ગુજરાત સરકાર";
        welcomeText.innerText = "સત્તાવાર સરકારની વેબસાઈટમાં આપનું સ્વાગત છે";
        descriptionText.innerText = "આ એક સત્તાવાર વેબસાઈટ છે જે માહિતી પ્રદાન કરે છે.";
        languageButton.innerText = "ગુજરાતી ⬇";
    } else {
        govText.innerText = "Government of Gujarat";
        welcomeText.innerText = "Welcome to the Government Website";
        descriptionText.innerText = "This is a government website providing official information.";
        languageButton.innerText = "English ⬇";
    }
}

// CHAT FUNCTIONALITY
const messagesDiv = document.getElementById("messages");
const messageInput = document.getElementById("messageInput");
const sendButton = document.getElementById("sendButton");

// Prompt the user for a sender name
const sender = prompt("Enter your name:");

// Fetch messages periodically
function fetchMessages() {
    fetch("fetch_messages.php")
        .then((response) => response.json())
        .then((messages) => {
            messagesDiv.innerHTML = ""; // Clear messages
            messages.forEach((msg) => {
                const messageElement = document.createElement("div");
                messageElement.textContent = `${msg.sender}: ${msg.message}`;
                messagesDiv.appendChild(messageElement);
            });
            messagesDiv.scrollTop = messagesDiv.scrollHeight; // Scroll to the bottom
        });
}

// Send a message
sendButton.addEventListener("click", () => {
    const message = messageInput.value;
    if (message.trim() !== "") {
        fetch("save_message.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `sender=${sender}&message=${encodeURIComponent(message)}`
        }).then(() => {
            messageInput.value = ""; // Clear input
            fetchMessages(); // Refresh messages
        });
    }
});

// Fetch messages every 2 seconds
setInterval(fetchMessages, 2000);
fetchMessages();

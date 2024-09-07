<div>
    <h2>Stream Created Successfully!</h2>
    <p>Your stream link is:</p>
    <input type="text" value="{{ $streamLink }}" id="streamLink" readonly>
    <button onclick="copyToClipboard()">Copy Link</button>

    <script>
        function copyToClipboard() {
            var copyText = document.getElementById("streamLink");
            copyText.select();
            document.execCommand("copy");
            alert("Link copied to clipboard: " + copyText.value);
        }
    </script>
</div>

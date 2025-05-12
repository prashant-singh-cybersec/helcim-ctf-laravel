@extends('base')

@section('body')
    <div
        class="min-h-screen bg-gradient-to-r from-yellow-100 via-pink-100 to-purple-100 flex items-center justify-center -mt-5">
        <div class="bg-white p-10 rounded-xl shadow-2xl w-full max-w-3xl animate-fadeIn">
            <h2 class="text-4xl font-extrabold text-center text-indigo-700 mb-8 animate-bounce">Share Your Feedback!</h2>
            <p class="text-center text-gray-500 italic mt-4">
                Share your feedback or...try something sneaky 👀
            </p>
            <br>


            <!-- Feedback Form -->
            <form id="feedbackForm" class="space-y-6">
                <!-- Toolbar -->
                <div class="flex justify-center space-x-4 mb-4">
                    <button type="button" onclick="formatText('bold')" title="Bold" class="toolbar-button">
                        <i class="fas fa-bold"></i>
                    </button>
                    <button type="button" onclick="formatText('italic')" title="Italic" class="toolbar-button">
                        <i class="fas fa-italic"></i>
                    </button>
                    <button type="button" onclick="formatText('underline')" title="Underline" class="toolbar-button">
                        <i class="fas fa-underline"></i>
                    </button>
                    <button type="button" onclick="formatText('insertLineBreak')" title="Line Break" class="toolbar-button">
                        <i class="fas fa-level-down-alt"></i>
                    </button>
                </div>

                <!-- Content Editable Div -->
                <div id="feedbackEditor" contenteditable="true"
                    class="w-full min-h-[180px] p-4 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 overflow-y-auto transition duration-300"
                    placeholder="Type your feedback here...">
                </div>

                <!-- Hidden Textarea for Submission -->
                <textarea id="feedbackInput" name="feedback" class="hidden"></textarea>

                <button type="submit"
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-300">
                    Submit Feedback
                </button>
            </form>

            <!-- Feedback List -->
            <h2 class="text-2xl font-bold text-gray-800 mt-12 mb-4">Previously Submitted Feedbacks</h2>
            <ul class="space-y-4">
                @forelse ($feedbacks as $feedback)
                    <li class="p-4 bg-gray-100 rounded-lg shadow">{!! $feedback !!}</li>
                @empty
                    <li class="text-gray-500 text-center">No feedback submitted yet.</li>
                @endforelse
            </ul>

            <!-- Response Message -->
            <div id="response" class="mt-6 text-center"></div>
        </div>
    </div>

    <!-- Load FontAwesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"
        crossorigin="anonymous"></script>
    <!-- Load DOMPurify for frontend sanitization -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.10/purify.min.js"></script>

    <script>
        function formatText(command) {
            if (command === 'insertLineBreak') {
                document.execCommand('insertHTML', false, '<br>');
            } else {
                document.execCommand(command, false, null);
            }
        }

        document.getElementById('feedbackForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const feedbackHtml = document.getElementById('feedbackEditor').innerHTML;
            const sanitizedFeedback = DOMPurify.sanitize(feedbackHtml);

            document.getElementById('feedbackInput').value = sanitizedFeedback;

            const response = await fetch('/feedback', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ feedback: sanitizedFeedback }),
            });

            const data = await response.json();
            const responseDiv = document.getElementById('response');

            if (data.success) {
                responseDiv.innerHTML = `<p class="text-green-600 font-bold animate-pulse mt-4">Feedback submitted successfully!</p>`;
                setTimeout(() => location.reload(), 1000);
            } else {
                responseDiv.innerHTML = `<p class="text-red-600 font-bold animate-pulse mt-4">${data.error || 'Something went wrong.'}</p>`;
            }
        });
    </script>

    <style>
        /* Toolbar Button Styling */
        .toolbar-button {
            padding: 10px;
            background: white;
            color: #4f46e5;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, background 0.2s;
        }

        .toolbar-button:hover {
            background: #4f46e5;
            color: white;
            transform: scale(1.1);
        }

        /* Animation Keyframes */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
@endsection
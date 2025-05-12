@extends('base')

@section('body')
<div class="min-h-screen bg-gradient-to-r from-yellow-100 via-pink-100 to-purple-100 flex flex-col pt-10 -mt-5">
    <!-- Main Content -->
    <main class="container mx-auto flex-1 flex items-center justify-center text-gray-800">
        <div class="text-center space-y-10 max-w-4xl px-6">
            <!-- Project Title -->
            <h2 class="text-5xl font-extrabold text-gray-900 leading-tight">
                🚀 Welcome to Invotek's Invoice Management System
            </h2>

            <!-- Description -->
            <p class="text-lg text-gray-700 leading-relaxed">
                Dive into the world of invoice management—with a twist! This app isn’t just about invoices—it’s a battlefield for your hacking skills.  
                From creating customers to issuing invoices, explore a fully-featured system laced with vulnerabilities from the  
                <span class="font-semibold text-indigo-600">OWASP Top 10</span> and sneaky business logic bugs.  
                <br><br>
                Oh, and the premium features? 🔐 Available only to our elite <span class="font-bold text-green-600">paid users</span>.
            </p>

            <!-- Features Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-6 bg-white rounded-lg shadow-xl hover:shadow-2xl transform transition duration-300 hover:scale-105">
                    <h3 class="text-xl font-bold text-indigo-600 mb-2">🖥️ Interactive Dashboard</h3>
                    <p class="text-sm text-gray-600">Seamlessly manage customers and invoices. But beware—there’s more than meets the eye!</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-xl hover:shadow-2xl transform transition duration-300 hover:scale-105">
                    <h3 class="text-xl font-bold text-indigo-600 mb-2">💳 Invoice Mastery</h3>
                    <p class="text-sm text-gray-600">Create polished invoices. Experience exclusive features designed for pro users.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-xl hover:shadow-2xl transform transition duration-300 hover:scale-105">
                    <h3 class="text-xl font-bold text-indigo-600 mb-2">🛡️ CTF Challenges</h3>
                    <p class="text-sm text-gray-600">Exploit OWASP Top 10 vulnerabilities and logic flaws. Showcase your pentesting prowess!</p>
                </div>
            </div>

            <!-- User Roles Section -->
            <div class="p-6 bg-gradient-to-br from-indigo-100 to-purple-200 rounded-lg shadow-lg max-w-2xl mx-auto text-left space-y-3">
                <h3 class="text-2xl font-bold text-indigo-700">🧑‍💻 User Roles</h3>
                <p class="text-gray-700 text-sm">
                    <strong>👑 Admin:</strong> The overseer. Has full control over customers, invoices, and user roles.<br>
                    <strong>👤 User:</strong> Can have read access to basic functionalities.<br>
                </p>
            </div>

            <!-- CTA -->
            <div>
                <a href="/register" class="inline-block px-8 py-3 bg-indigo-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg transition">
                    Get Started
                </a>
            </div>
        </div>
    </main>
</div>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div id="app" class="container mx-auto px-4 py-8">
        <!-- Loading State -->
        <div id="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading...</p>
        </div>

        <!-- Login Form -->
        <div id="loginForm" class="hidden max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
            <form id="loginFormElement">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="email" type="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" 
                           id="password" type="password" placeholder="Enter your password" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" 
                            type="submit">
                        Sign In
                    </button>
                </div>
                <div class="text-center mt-4">
                    <button type="button" id="showRegister" class="text-blue-500 hover:text-blue-700">
                        Don't have an account? Register
                    </button>
                </div>
            </form>
        </div>

        <!-- Register Form -->
        <div id="registerForm" class="hidden max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-center mb-6">Register</h2>
            <form id="registerFormElement">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="regName">
                        Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="regName" type="text" placeholder="Enter your name" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="regEmail">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="regEmail" type="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="regPassword">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="regPassword" type="password" placeholder="Enter your password" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="regPasswordConfirm">
                        Confirm Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="regPasswordConfirm" type="password" placeholder="Confirm your password" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" 
                            type="submit">
                        Register
                    </button>
                </div>
                <div class="text-center mt-4">
                    <button type="button" id="showLogin" class="text-blue-500 hover:text-blue-700">
                        Already have an account? Login
                    </button>
                </div>
            </form>
        </div>

        <!-- Dashboard -->
        <div id="dashboard" class="hidden">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                        <p class="text-gray-600">Welcome back, <span id="userName"></span>!</p>
                    </div>
                    <div class="flex space-x-4">
                        <button id="profileBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Profile
                        </button>
                        <button id="logoutBtn" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Logout
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Role</p>
                            <p class="text-lg font-semibold text-gray-900" id="userRole"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Status</p>
                            <p class="text-lg font-semibold text-gray-900">Active</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6" id="totalUsersCard" style="display: none;">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-lg font-semibold text-gray-900" id="totalUsers"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Section -->
            <div id="profileSection" class="hidden bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold mb-4">Profile Information</h2>
                <form id="profileForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="profileName">
                                Name
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   id="profileName" type="text" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="profileEmail">
                                Email
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   id="profileEmail" type="email" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="profilePhone">
                                Phone
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   id="profilePhone" type="text">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="profileAvatar">
                                Avatar URL
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   id="profileAvatar" type="url">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="profileBio">
                            Bio
                        </label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                  id="profileBio" rows="3"></textarea>
                    </div>
                    <div class="mt-6 flex space-x-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Profile
                        </button>
                        <button type="button" id="cancelProfile" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Admin Users Section -->
            <div id="usersSection" class="hidden bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">User Management</h2>
                    <button id="addUserBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Add User
                    </button>
                </div>
                <div id="usersTable" class="overflow-x-auto">
                    <!-- Users table will be populated here -->
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        <div id="errorMessage" class="hidden fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded max-w-md">
            <span id="errorText"></span>
        </div>

        <!-- Success Messages -->
        <div id="successMessage" class="hidden fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded max-w-md">
            <span id="successText"></span>
        </div>
    </div>

    <script>
        // Global variables
        let token = localStorage.getItem('auth_token');
        let currentUser = null;

        // API configuration
        const API_BASE = '/api';
        
        // Configure axios
        axios.defaults.baseURL = API_BASE;
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }

        // DOM elements
        const elements = {
            loading: document.getElementById('loading'),
            loginForm: document.getElementById('loginForm'),
            registerForm: document.getElementById('registerForm'),
            dashboard: document.getElementById('dashboard'),
            profileSection: document.getElementById('profileSection'),
            usersSection: document.getElementById('usersSection'),
            errorMessage: document.getElementById('errorMessage'),
            successMessage: document.getElementById('successMessage')
        };

        // Utility functions
        function showElement(element) {
            element.classList.remove('hidden');
        }

        function hideElement(element) {
            element.classList.add('hidden');
        }

        function showError(message) {
            document.getElementById('errorText').textContent = message;
            showElement(elements.errorMessage);
            setTimeout(() => hideElement(elements.errorMessage), 5000);
        }

        function showSuccess(message) {
            document.getElementById('successText').textContent = message;
            showElement(elements.successMessage);
            setTimeout(() => hideElement(elements.successMessage), 5000);
        }

        function hideAllSections() {
            Object.values(elements).forEach(el => hideElement(el));
        }

        // Authentication functions
        async function checkAuth() {
            if (!token) {
                showLoginForm();
                return;
            }

            try {
                const response = await axios.get('/auth/me');
                currentUser = response.data.data;
                showDashboard();
            } catch (error) {
                localStorage.removeItem('auth_token');
                token = null;
                delete axios.defaults.headers.common['Authorization'];
                showLoginForm();
            }
        }

        async function login(email, password) {
            try {
                const response = await axios.post('/auth/login', { email, password });
                const { token: newToken, user } = response.data.data;
                
                token = newToken;
                currentUser = user;
                localStorage.setItem('auth_token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                
                showSuccess('Login successful!');
                showDashboard();
            } catch (error) {
                showError(error.response?.data?.message || 'Login failed');
            }
        }

        async function register(name, email, password, password_confirmation) {
            try {
                const response = await axios.post('/auth/register', {
                    name,
                    email,
                    password,
                    password_confirmation
                });
                
                const { token: newToken, user } = response.data.data;
                
                token = newToken;
                currentUser = user;
                localStorage.setItem('auth_token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                
                showSuccess('Registration successful!');
                showDashboard();
            } catch (error) {
                const message = error.response?.data?.message || 'Registration failed';
                const errors = error.response?.data?.errors;
                
                if (errors) {
                    const errorMessages = Object.values(errors).flat().join(', ');
                    showError(errorMessages);
                } else {
                    showError(message);
                }
            }
        }

        async function logout() {
            try {
                await axios.post('/auth/logout');
            } catch (error) {
                // Ignore logout errors
            }
            
            localStorage.removeItem('auth_token');
            token = null;
            currentUser = null;
            delete axios.defaults.headers.common['Authorization'];
            
            showSuccess('Logged out successfully!');
            showLoginForm();
        }

        // Dashboard functions
        async function loadDashboard() {
            try {
                const response = await axios.get('/dashboard');
                const data = response.data.data;
                
                document.getElementById('userName').textContent = data.user.name;
                document.getElementById('userRole').textContent = data.user.role.charAt(0).toUpperCase() + data.user.role.slice(1);
                
                if (data.user.role === 'admin' && data.dashboard_stats.total_users) {
                    document.getElementById('totalUsers').textContent = data.dashboard_stats.total_users;
                    showElement(document.getElementById('totalUsersCard'));
                }
            } catch (error) {
                showError('Failed to load dashboard data');
            }
        }

        async function loadProfile() {
            try {
                const response = await axios.get('/profile');
                const user = response.data.data;
                
                document.getElementById('profileName').value = user.name || '';
                document.getElementById('profileEmail').value = user.email || '';
                document.getElementById('profilePhone').value = user.phone || '';
                document.getElementById('profileBio').value = user.bio || '';
                document.getElementById('profileAvatar').value = user.avatar || '';
            } catch (error) {
                showError('Failed to load profile');
            }
        }

        async function updateProfile(formData) {
            try {
                const response = await axios.put('/profile', formData);
                currentUser = response.data.data;
                showSuccess('Profile updated successfully!');
                hideElement(elements.profileSection);
            } catch (error) {
                const message = error.response?.data?.message || 'Failed to update profile';
                const errors = error.response?.data?.errors;
                
                if (errors) {
                    const errorMessages = Object.values(errors).flat().join(', ');
                    showError(errorMessages);
                } else {
                    showError(message);
                }
            }
        }

        // UI functions
        function showLoginForm() {
            hideAllSections();
            showElement(elements.loginForm);
        }

        function showRegisterForm() {
            hideAllSections();
            showElement(elements.registerForm);
        }

        function showDashboard() {
            hideAllSections();
            showElement(elements.dashboard);
            loadDashboard();
        }

        function showProfile() {
            hideElement(elements.usersSection);
            showElement(elements.profileSection);
            loadProfile();
        }

        // Event listeners
        document.getElementById('loginFormElement').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            await login(email, password);
        });

        document.getElementById('registerFormElement').addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = document.getElementById('regName').value;
            const email = document.getElementById('regEmail').value;
            const password = document.getElementById('regPassword').value;
            const password_confirmation = document.getElementById('regPasswordConfirm').value;
            
            if (password !== password_confirmation) {
                showError('Passwords do not match');
                return;
            }
            
            await register(name, email, password, password_confirmation);
        });

        document.getElementById('showRegister').addEventListener('click', showRegisterForm);
        document.getElementById('showLogin').addEventListener('click', showLoginForm);
        document.getElementById('logoutBtn').addEventListener('click', logout);
        document.getElementById('profileBtn').addEventListener('click', showProfile);
        document.getElementById('cancelProfile').addEventListener('click', () => {
            hideElement(elements.profileSection);
        });

        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                name: document.getElementById('profileName').value,
                email: document.getElementById('profileEmail').value,
                phone: document.getElementById('profilePhone').value,
                bio: document.getElementById('profileBio').value,
                avatar: document.getElementById('profileAvatar').value
            };
            await updateProfile(formData);
        });

        // Initialize app
        checkAuth();
    </script>
</body>
</html>

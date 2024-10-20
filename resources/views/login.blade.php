@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h1>Login</h1>
    <div id="loginMessage" class="mt-3"></div>
    <form id="loginForm">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
@endsection

@section('scripts')
    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/api/v1/login', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                },
            })
                .then(response => {
                    const statusCode = response.status;
                    return response.json().then(data => ({status: statusCode, body: data}));
                })
                .then(({status, body}) => {
                    const messageDiv = document.getElementById('loginMessage');
                    switch (status) {
                        case 200:
                            messageDiv.innerHTML = '<div class="alert alert-success">Login successful!</div>';
                            localStorage.setItem('token', body.access_token);
                            setTimeout(function () {
                                window.location.href = `{{ route('book.list') }}`;
                            }, 2000)
                            break;
                        case 401:
                            messageDiv.innerHTML = '<div class="alert alert-danger">Invalid credentials. Please try again.</div>';
                            break;
                        case 422:
                            let errorMessage = '<div class="alert alert-danger"><ul>';
                            Object.values(body.errors).forEach(error => {
                                errorMessage += `<li>${error}</li>`;
                            });
                            errorMessage += '</ul></div>';
                            messageDiv.innerHTML = errorMessage;
                            break;
                        default:
                            messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loginMessage').innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                });
        });
    </script>
@endsection

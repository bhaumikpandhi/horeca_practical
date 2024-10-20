@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <h1>Register</h1>
    <form id="registerForm">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <div id="registerMessage" class="mt-3"></div>
@endsection

@section('scripts')
    <script>
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/api/v1/register', {
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
                    const messageDiv = document.getElementById('registerMessage');
                    switch (status) {
                        case 201:
                            messageDiv.innerHTML = '<div class="alert alert-success">Registration successful!</div>';
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
                    document.getElementById('registerMessage').innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                });
        });
    </script>
@endsection

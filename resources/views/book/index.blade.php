@extends('layouts.app')

@section('title', 'Books List')

@section('content')
    <h1>Books List</h1>
    <div id="action_info_box" class="mt-3"></div>

    <div class="row mb-3">
        <div class="col-md-5">
            <input type="text" id="authorFilter" class="form-control" placeholder="Filter by Author">
        </div>
        <div class="col-md-5">
            <select id="genreFilter" class="form-select">
                <option value="">All Genres</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->name }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" id="creatBtn" data-bs-toggle="modal" data-bs-target="#createBookModal">
                Create Book
            </button>
        </div>
    </div>

    <div id="booksList" class="row"></div>

    <nav>
        <ul class="pagination justify-content-end" id="booksPagination"></ul>
    </nav>

    @include('book.create_modal')

    @include('book.edit_modal')
@endsection

@section('scripts')
    <script>
        let currentPage = 1;
        let totalPages = 1;
        let token = localStorage.getItem('token');

        function loadBooks(page = 1) {
            const author = document.getElementById('authorFilter').value;
            const genre = document.getElementById('genreFilter').value;

            const url = new URL('/api/v1/books', window.location.origin);
            const params = new URLSearchParams({page});

            if (author) {
                params.append('filter[author]', author);
            }

            if (genre) {
                params.append('filter[genre]', genre);
            }

            url.search = params.toString();

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const booksList = document.getElementById('booksList');
                    booksList.innerHTML = '';

                    if (data.data.length === 0) {
                        booksList.innerHTML = '<div class="col-12"><p>No books found</p></div>';
                    } else {
                        data.data.forEach(book => {
                            let bookHtml = `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">${book.title}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">${book.author}</h6>
                                <p class="card-text">Genre: ${book.genre.name}</p>`;

                            // Only show Edit and Delete buttons if logged-in
                            if (typeof token !== 'undefined' && token) {
                                bookHtml += `
                            <button class="btn btn-sm btn-primary" onclick="editBook(${book.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteBook(${book.id})">Delete</button>`;
                            }

                            bookHtml += `
                        </div>
                    </div>
                </div>`;

                            booksList.innerHTML += bookHtml;
                        });
                    }

                    currentPage = data.meta.current_page;
                    totalPages = data.meta.last_page;
                    updatePagination();
                });
        }


        function updatePagination() {
            const pagination = document.getElementById('booksPagination');
            pagination.innerHTML = '';

            if (totalPages > 1) {
                for (let i = 1; i <= totalPages; i++) {
                    pagination.innerHTML += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="loadBooks(${i})">${i}</a>
                        </li>
                    `;
                }
            }
        }

        function createBook(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const jsonData = Object.fromEntries(formData.entries());

            fetch('/api/v1/books', {
                method: 'POST',
                body: JSON.stringify(jsonData),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
            })
                .then(response => {
                    const statusCode = response.status;
                    return response.json().then(data => ({status: statusCode, body: data}));
                })
                .then(({status, data}) => {
                    const messageDiv = document.getElementById('create_book_info');
                    switch (status) {
                        case 201:
                            messageDiv.innerHTML = '<div class="alert alert-success">Book Created!</div>';
                            loadBooks();
                            $('#createBookModal').modal('hide');
                            document.getElementById('createBookForm').reset();
                            messageDiv.innerHTML = '';
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
                });
        }

        function editBook(id) {
            fetch(`/api/v1/books/${id}`)
                .then(response => response.json())
                .then(response => {
                    document.getElementById('editBookId').value = response.data.id;
                    document.getElementById('editTitle').value = response.data.title;
                    document.getElementById('editAuthor').value = response.data.author;
                    document.getElementById('editGenre_id').value = response.data.genre_id;
                    document.getElementById('editPublishedDate').value = response.data.published_date;
                    $('#editBookModal').modal('show');
                });
        }

        function updateBook(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const jsonData = Object.fromEntries(formData.entries());
            const id = document.getElementById('editBookId').value;

            fetch(`/api/v1/books/${id}`, {
                method: 'PUT',
                body: JSON.stringify(jsonData),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-HTTP-Method-Override': 'PUT'
                },
            })
                .then(response => {
                    const statusCode = response.status;
                    return response.json().then(data => ({status: statusCode, body: data}));
                })
                .then(({status, data}) => {
                    const messageDiv = document.getElementById('edit_book_info');
                    switch (status) {
                        case 200:
                            messageDiv.innerHTML = '<div class="alert alert-success">Book updated!</div>';
                            loadBooks();
                            $('#editBookModal').modal('hide');
                            document.getElementById('editBookForm').reset();
                            messageDiv.innerHTML = '';
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
                });
        }

        function deleteBook(id) {
            if (confirm('Are you sure you want to delete this book?')) {
                fetch(`/api/v1/books/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                })
                    .then(response => {
                        const statusCode = response.status;
                        return response.json().then(data => ({status: statusCode, body: data}));
                    })
                    .then(({status, data}) => {
                        const messageDiv = document.getElementById('edit_book_info');
                        switch (status) {
                            case 200:
                                loadBooks();
                                break;
                            default:
                                messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                        }
                    });

            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadBooks();

            if (!token) {
                $('#creatBtn').remove();
            }

            // Debounce function to delay API calls
            function debounce(func, delay) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), delay);
                };
            }

            // Apply debounce to input event
            const debouncedLoadBooks = debounce(() => loadBooks(), 500); // 500ms delay

            document.getElementById('authorFilter').addEventListener('input', debouncedLoadBooks);
            document.getElementById('genreFilter').addEventListener('change', () => loadBooks());

            document.getElementById('createBookForm').addEventListener('submit', createBook);
            document.getElementById('editBookForm').addEventListener('submit', updateBook);
        });

    </script>
@endsection

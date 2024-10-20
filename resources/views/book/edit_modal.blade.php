<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="edit_book_info" class="mt-3"></div>
                <form id="editBookForm">
                    <input type="hidden" id="editBookId">
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAuthor" class="form-label">Author</label>
                        <input type="text" class="form-control" id="editAuthor" name="author" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPublishedDate" class="form-label">Published Date</label>
                        <input type="date" class="form-control" id="editPublishedDate" name="published_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGenre_id" class="form-label">Genre</label>
                        <select class="form-select" id="editGenre_id" name="genre_id" required>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

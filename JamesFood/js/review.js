const comment = document.getElementById('comment');
const cancelComment = document.getElementById('cancel-comment');
const addAComment = document.getElementById('add_a_comment');
const contentId = document.getElementById('content_id');
const rootPath = document.getElementById('root_path');

// Event listener that allows the addition of new comments
addAComment.addEventListener('click', async (e) => {
    // In case there is no comment, show an alert and return
    if(comment.value.trim() === '') {
        alert('The comment field cannot be empty.');
        return;
    }

    const formData = new FormData();
    formData.append('comment', comment.value);
    formData.append('content_id', contentId.value);

    const spinner = document.getElementById('spinner');
    const commentsContainer = document.getElementById('comments-container');
    const commentsHeader = document.getElementById('comments-header');

    const options = {
        method: 'post',
        body: formData
    };
    
    spinner.classList.remove('d-none');

    // Using fetch API to save comments in the database
    const response = await fetch(`${rootPath.value}/post-comment.php`, options);
    // Obtaining the json with all the comments
    const data = await response.json();

    // Repaint all the comments, including the most recent ones
    let html = ``;
    data.comments.forEach((el) => {
        html += `
            <div class="bg-success-subtle rounded-3 mb-3 p-3 d-flex w-100">
                <div>
                    <div class="logged-user bg-success rounded-circle text-white text-center me-3">${el.first_letter}</div>
                </div>
                <div class="w-100">
                    <div>
                        <small class="fw-bold float-start">${el.full_name}</small>
                        <small class="float-end">${el.date}</small>
                    </div>
                    <div class="clearfix mb-2"></div>
                    <div>
                        ${el.comment}
                    </div>
                </div>
            </div>
        `;
    });

    commentsContainer.innerHTML = html;
    commentsHeader.innerHTML = data.total_comments;
    spinner.classList.add('d-none');
    comment.value = '';
});

cancelComment.addEventListener('click', (e) => {
    comment.value = '';
});
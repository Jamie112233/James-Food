document.addEventListener('click', async (e) => {
    if(e.target.dataset.action === 'delete user') {
        e.preventDefault();
        if(confirm('Are you sure you want to delete this user?')) {
            e.target.closest('form').submit();
        }
    }
});
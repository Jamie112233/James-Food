CKEDITOR.replace('description');
document.getElementById('sidebar').style.height = '150%';

const inpFile = document.getElementById('image');

inpFile.addEventListener('input', (e) => {
    const allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
    const file = inpFile.files[0];

    if (file == undefined) {
        return false;
    }

    if (allowedExtensions.indexOf(file.name.split('.').pop()) === -1) {
        alert('The file you are trying to upload is not allowed. Just allowed file images.');
        inpFile.value = '';
    }
});

const deleteThisImage = document.getElementById('delete-this-image');

if(deleteThisImage) {
    deleteThisImage.addEventListener('click', (e) => {
        e.preventDefault();
        deleteThisImage.closest('.mb-3').remove();
        document.getElementById('image_has_been_deleted').value = 'true';
    });
}
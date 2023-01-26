window.onload = (e) => {
    let deleteButton = document.querySelector('opciones-modelo button.btn.btn-danger');
    deleteButton.addEventListener('click', (e) => {
        fetch('api/deleteModel.php', {
            method: 'DELETE',
            headers: {

            }
        })
    });
}
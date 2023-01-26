let tempModelFileInput;
let tempModelFileButton;

function onSubmitModelListener(e) {
    debugger;
    const file = tempModelFileInput.files[0];
    handleFile(file);
}

function handleFile(file) {
    new FileUpload(file);
}

function FileUpload(file) {
    const fd = new FormData();
    fd.append('modelFile', file);

    fetch('api/uploadTempModel.php', {
        method: 'POST',
        body: fd,
    }).then((res => {
        if (res.ok && res.status) {
            return res.json();
        }
    })).then((data) => {
        viewer.loadTempModel(data.tmpModelUrl);
    })
}

window.onload = () => {
    tempModelFileInput = document.querySelector('input[type="file"]#tempModelFile');
    // tempModelFileInput.addEventListener('change', onSubmitModelListener);

    tempModelFileButton = document.querySelector('button#tempModelFileButton');
    tempModelFileButton.addEventListener('click', onSubmitModelListener);
}
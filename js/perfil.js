let modelosData;
let currentPage = 1;
const loadmoreIncrement = 3;
const modelsContainer = document.getElementById("models-container");
const loadmoreButton = document.getElementById("loadmore-button");
loadmoreButton.setAttribute('disabled', true);

const modelsCountText = document.getElementById("models-count");
const modelsTotalText = document.getElementById("models-total");

let modelsLimit
let pageCount;

function createPreview(data) {

    const component =
        `<div class="col">
        <a href="visualizador.php?modelo=${data.modelo_url}">
            <div class="card">
                <img class="p-2" src="${ (data.thumbnail_url)? 'uploads/modelos_thumbnail/'+ data.thumbnail_url : 'images/imagen2.png'}" alt="${data.nombre}">
                <div class="card-body">
                    <p class="card-text fw-bold">${data.nombre}</p>
                </div>
            </div>
        </a>
    </div>`;

    return component;
}

function addNewModelPreview(pageIndex) {
    currentPage = pageIndex;

    buttonStatus();

    const startRange = (pageIndex - 1) * loadmoreIncrement;
    const endRange = pageIndex * loadmoreIncrement > modelsLimit ? modelsLimit : pageIndex * loadmoreIncrement;

    modelsCountText.innerHTML = endRange;

    for (let i = startRange + 1; i <= endRange; i++) {
        const preview = createPreview(modelosData[i - 1]);
        modelsContainer.innerHTML += preview;
    }
}

function buttonStatus() {
    if (pageCount === currentPage) {
        loadmoreButton.classList.add("disabled");
        loadmoreButton.setAttribute("disabled", true);
        loadmoreButton.classList.add('d-none');
    } else {
        loadmoreButton.classList.remove("disabled");
        loadmoreButton.removeAttribute("disabled");
        loadmoreButton.classList.remove('d-none');
    }
}

function getModelsFromServer(username) {
    fetch('api/preview.php?id=' + username).then(res => {
        if (res.ok && res.status == 200) {
            res.json().then(res => {
                processData(res);
            });
        }
    })
}

function processData(data) {
    modelosData = data;
    modelsTotalText.innerHTML = data.length;

    modelsLimit = data.length
    pageCount = Math.ceil(data.length / loadmoreIncrement);
    if (data.length === 0) {
        return;
    }
    document.querySelector('section#empty-user').classList.add('d-none')
    addNewModelPreview(currentPage);
}

function onSubmitImageListener(e) {
    const file = this.files[0];
    handleImage(file);
}

function handleImage(file) {
    if (!file.type.startsWith('image/')) {
        alert('Este formato de archivo no es soportado');
        return;
    }
    const img = document.querySelector('div.change-profile-photo-modal img.change-profile-photo-modal__image');
    img.file = file;
    img.oldPicture = img.src;

    const reader = new FileReader();
    document.querySelector('button#submitPhotoButton').removeAttribute('disabled')
    reader.onload = (e) => { img.src = e.target.result };
    reader.readAsDataURL(file);
}

function uploadProfilePhoto() {
    const img = document.querySelector('div.change-profile-photo-modal img.change-profile-photo-modal__image');
    if (!img.file) {
        return;
    }
    new FileUpload(img, img.file);
}

function FileUpload(img, file) {
    // With form data
    const fd = new FormData();
    fd.append('profilePhoto', file);

    // const reader = new FileReader();
    // this.ctrl = createThrobber(img);
    // const req = new XMLHttpRequest();
    // this.req = req;

    fetch('api/uploadProfilePicture.php', {
        method: 'POST',
        body: fd
    }).then((res) => {
        if (res.ok) {
            res.json().then((value) => {
                document.querySelector('img#profilePhoto').src = value.url;
                document.querySelector('img.navbar__picture').src = value.url;
                showToast('Profile picture updated successfully');
                document.querySelector('button#close-change-profile-photo').click();
            })
        } else {
            throw new Error('Something went wrong');
        }
    }).catch((reason) => {
        showToast('Something went wrong trying to update the profile picture', 'danger');
        img.src = img.oldPicture;
        document.querySelector('button#close-change-profile-photo').click();
    })

    // const self = this;
    // this.req.upload.addEventListener('progress', (e) => {
    //     if (e.lengthComputable) {
    //         const percentage = Math.round((e.loaded * 100) / e.total);
    //         self.ctrl.update(percentage);
    //     }
    // }, false);

    // req.upload.addEventListener('load', (e) => {
    //     self.ctrl.update(100);
    //     const canvas = self.ctrl.ctx.canvas;
    //     canvas.parentNode.removeChild(canvas);
    // }, false);

    // req.open('POST', 'api/uploadProfilePicture.php', true
    // )
    // req.send(fd);
    // req.overrideMimeType('text/plain; charset=x-user-defined-binary');
    // reader.onload = (evt) => {
    //     req.send(evt.target.result);
    // };
    // reader.readAsBinaryString(file);
}

function createThrobber(img) {
    const throbberWidth = 64;
    const throbberHeight = 6;
    const throbber = document.createElement('canvas');
    throbber.classList.add('upload-progress');
    throbber.setAttribute('width', throbberWidth);
    throbber.setAttribute('height', throbberHeight);
    img.parentNode.appendChild(throbber);
    throbber.ctx = throbber.getContext('2d');
    throbber.ctx.fillStyle = 'orange';
    throbber.update = (percent) => {
        throbber.ctx.fillRect(0, 0, throbberWidth * percent / 100, throbberHeight);
        if (percent === 100) {
            throbber.ctx.fillStyle = 'green';
        }
    }
    throbber.update(0);
    return throbber;
}


window.onload = function () {
    loadmoreButton.addEventListener("click", () => {
        addNewModelPreview(currentPage + 1);
    })
    let username = document.getElementById('usernameHeader').innerText;
    username = username.substring(1, username.length);
    getModelsFromServer(username);

    const profilePhotoInput = document.getElementById('profilePhotoInput');
    document.getElementById('selectProfilePhotoButton').addEventListener('click', (e) => {
        profilePhotoInput.click();
    })

    profilePhotoInput.addEventListener('change', onSubmitImageListener);

    document.querySelector('div#changeProfilePhotoModal').addEventListener('hide.bs.modal', (e) => {
        document.querySelector('button#submitPhotoButton').setAttribute('disabled', true)
        const oldPicture = document.querySelector('img#profilePhoto').src;
        document.querySelector('div.change-profile-photo-modal img.change-profile-photo-modal__image').src = oldPicture;
    })

    // document.querySelector('button#cancelPhotoButton').addEventListener('click', (e) => {
    //     const img = ;
    //     img.src = document.querySelector('img#profilePhoto').src;
    // })

    const submitPhotoButton = document.querySelector('button#submitPhotoButton');
    submitPhotoButton.addEventListener('click', (e) => {
        e.stopPropagation();
        uploadProfilePhoto();
    })

}


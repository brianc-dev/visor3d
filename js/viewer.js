// autor Brian Caldera 2022

let viewer, fileManager;
let defaultBehavior = false;

let downloadedModel;

const targetDir = 'uploads/modelos/';

const CANVAS_HOLDER = "canvas-placeholder";
const DEFAULT_BG_COLOR = '#000000';

/**
 * A class container for the whole app
 */
class Viewer {
    /**
     * The canvas object
     */
    #canvas;
    #cameraManager;
    #backgroundColor = DEFAULT_BG_COLOR;
    #mostrarMesh = false;

    #scale = 1;

    #strokeWeight = 0.1;
    #strokeColor = '#000000';

    #fillColor = '#ffffff';

    #materialColor = '#DBDBDB';

    #modelShininess = 20;

    #useAmbientLight = false;

    #flipZAxis = true;
    get #ambientLightColor () { return this.#backgroundColor };

    /**
     * 1 = Ambient
     * 2 = Specular
     * 3 = Normal
     */
    #renderMode = 1;
    get isFullscreenMode() { return document.fullscreenElement }

    needsThumbnail = false;
    constructor() {
        if (typeof requestsThumbnail !== 'undefined') {
            if (requestsThumbnail) {
                this.needsThumbnail = true;
            }
        }
        // Set angle to degrees mode
        angleMode(DEGREES);
        // Set canvas into placeholder
        const parent = document.getElementById(CANVAS_HOLDER);
        this.#canvas = createCanvas(parent.clientWidth, parent.clientHeight, WEBGL);
        this.#canvas.parent(CANVAS_HOLDER);

        this.#cameraManager = new CameraManager();
        this.#cameraManager.centerCameraToOrigin();
    }
    onResizeCanvas(event) {
        this.#cameraManager.saveCameraPosition();
        let parent = document.getElementById(CANVAS_HOLDER);
        resizeCanvas(parent.clientWidth, parent.clientHeight);
        this.#cameraManager.restoreCameraPosition();
    }
    setParent(parent) {
        this.#canvas.parent(parent);
    }
    onMouseDragged(event) {
        return this.#cameraManager.onMouseDragged(event);
    }
    onMouseWheel(event) {
        return this.#cameraManager.onMouseWheel(event);
    }
    goFullscreen() {
        if (document.fullscreenEnabled) {
            if (!this.isFullscreenMode) {
                let parent = document.getElementById(CANVAS_HOLDER);
                if (parent.requestFullscreen) {
                    parent.requestFullscreen();
                }
            } else {
                document.exitFullscreen();
            }
        }
    }

    recenterCamera = () => {
        this.#cameraManager.recenterCamera();
    }

    cambiarColorDeFondo = (event) => {
        let color = event.target.value;
        this.#backgroundColor = color;
    }

    toggleMostrarMesh = (event) => {
        let value = event.target.checked;
        this.#mostrarMesh = value;
        (this.#mostrarMesh) ? stroke(this.#strokeColor) && strokeWeight(this.#strokeWeight) : noStroke();
    }

    toggleAmbientLights = (event) => {
        let value = event.target.checked;
        this.#useAmbientLight = value;
    }

    cambiarAmbientLightsColor = (event) => {
        this.#ambientLightColor = event.target.value;
    }

    cambiarStrokeColor = (event) => {
        this.#strokeColor = event.target.value;
        if (this.#mostrarMesh) stroke(this.#strokeColor);
    }
    cambiarMaterialColor = (event) => {
        this.#materialColor = event.target.value;
    }

    cambiarModoRenderizado = (event) => {
        let value = event.target.value;
        this.#renderMode = parseInt(value);
    }

    cambiarBrillantez = (event) => {
        let value = parseInt(event.target.value);
        this.#modelShininess = value;
    }

    escalarModelo = (event) => {
        const value = parseFloat(event.target.value);
        this.#scale = value;
    }

    toggleFlipZAxis = () => {
        this.#flipZAxis = !this.#flipZAxis;
    }

    loadTempModel = (file) => {
        loadModel('uploads/tmp_modelos/' + file, true, (model) => {
            downloadedModel = model;
        })
    }

    render = () => {
        switch (this.#renderMode) {
            case 1:
                ambientMaterial(this.#materialColor);
                break;
            case 2:
                shininess(this.#modelShininess);
                specularMaterial(this.#materialColor);
                break;
            case 3:
                normalMaterial();
                break;
            case 4:
                // Material emisivo
            break;
            default:
                
                break;
        }
    }

    sendThumbnail = () => {
        const canvas = document.querySelector('canvas');
        const imageData = canvas.toDataURL();
        const fd = new FormData();
        fd.append('imageUrl', imageData);
        fd.append('modeloUrl', modelFile);

        fetch('api/createModelThumbnail.php', {
            method: 'POST',
            body: fd
        }).then((res) => {
            if (res.ok) {
                console.log('Thumbnail created successfully');
            }
        })
    }

    onDraw = () => {
        this.#cameraManager.onDraw();
        scale(this.#scale);
        background(this.#backgroundColor);
        lights();
        directionalLight(255, 255, 255, 0, 1, 1);
        if (this.#flipZAxis) rotateZ(180);
        this.#useAmbientLight && ambientLight(this.#ambientLightColor);
        this.render();
        if (typeof downloadedModel === 'undefined') {
            box();
        } else {
            // Check if model needs thumbnail...
            model(downloadedModel);
            if (this.needsThumbnail) {
                this.sendThumbnail();
                this.needsThumbnail = false;
            }
        }
    }
}

class CameraManager {
    // Constants
    static get CAMERA_INITIAL_ANGLE() { return 45 }
    static get INITIAL_RADIO() { return 250 }
    static get MIN_RADIO() { return 50 }
    static get MAX_RADIO() { return 1000 }
    static get ZOOM_INCREMENT() { return 60 }
    static get TRANSITION_DURATION() { return 500 }

    cameraX = 0
    cameraY = 0
    cameraZ = 0

    isTransitioning = false

    #angle = CameraManager.CAMERA_INITIAL_ANGLE;
    #radio = CameraManager.INITIAL_RADIO;
    #currentCamera; // Active camera
    #currentCameraState;

    #cameras = []; // Array of cameras

    #transitionObject = {
        cameraPos: {
            initialCameraX: 0,
            initialCameraY: 0,
            initialCameraZ: 0,
            finalCameraX: 0,
            finalCameraY: 0,
            finalCameraZ: 0,
        },
        centerPos: {
            initialCenterX: 0,
            initialCenterY: 0,
            initialCenterZ: 0,
            finalCenterX: 0,
            finalCenterY: 0,
            finalCenterZ: 0,
        },
        transitionTime: 0
    }

    constructor() {
        const newCamera = createCamera();
        this.#currentCamera = newCamera;
        this.#cameras.push(newCamera);
        // newCamera.setPosition(0, 0, this.#radio);
        // newCamera.lookAt(0, 0, 0);
        setCamera(newCamera);
    }

    centerCameraToOrigin = () => {
        this.#radio = CameraManager.INITIAL_RADIO;
        this.cameraZ = sin(CameraManager.CAMERA_INITIAL_ANGLE) * this.#radio;
        this.cameraX = cos(CameraManager.CAMERA_INITIAL_ANGLE) * this.#radio;
        camera(this.cameraX, -100, this.cameraZ, 0, 0, 0, 0, 1, 0);
    }

    recenterCamera = () => {
        this.isTransitioning = true;
        const view = this.#currentCamera;
        const transitionObject = this.#transitionObject;
        transitionObject.transitionTime = 0
        transitionObject.cameraPos.initialCameraX = view.eyeX
        transitionObject.cameraPos.initialCameraY = view.eyeY
        transitionObject.cameraPos.initialCameraZ = view.eyeZ
        transitionObject.cameraPos.finalCameraZ = sin(CameraManager.CAMERA_INITIAL_ANGLE) * this.#radio
        transitionObject.cameraPos.finalCameraX = cos(CameraManager.CAMERA_INITIAL_ANGLE) * this.#radio
        transitionObject.cameraPos.finalCameraY = -100
        transitionObject.centerPos.initialCenterX = view.centerX
        transitionObject.centerPos.initialCenterY = view.centerY
        transitionObject.centerPos.initialCenterZ = view.centerZ
    }

    animateTransition = () => {
        const transitionObject = this.#transitionObject;
        const view = this.#currentCamera;
        if (!this.isTransitioning) return;

        transitionObject.transitionTime += deltaTime
        const transitionPercentage = norm(transitionObject.transitionTime, 0, CameraManager.TRANSITION_DURATION);

        const currentCameraX = transitionObject.cameraPos.initialCameraX + (transitionObject.cameraPos.finalCameraX - transitionObject.cameraPos.initialCameraX) * transitionPercentage
        const currentCameraY = transitionObject.cameraPos.initialCameraY + (transitionObject.cameraPos.finalCameraY - transitionObject.cameraPos.initialCameraY) * transitionPercentage
        const currentCameraZ = transitionObject.cameraPos.initialCameraZ + (transitionObject.cameraPos.finalCameraZ - transitionObject.cameraPos.initialCameraZ) * transitionPercentage

        const currentCenterX = transitionObject.centerPos.initialCenterX + (transitionObject.centerPos.finalCenterX - transitionObject.centerPos.initialCenterX) * transitionPercentage
        const currentCenterY = transitionObject.centerPos.initialCenterY + (transitionObject.centerPos.finalCenterY - transitionObject.centerPos.initialCenterY) * transitionPercentage
        const currentCenterZ = transitionObject.centerPos.initialCenterZ + (transitionObject.centerPos.finalCenterZ - transitionObject.centerPos.initialCenterZ) * transitionPercentage

        view.setPosition(currentCameraX, currentCameraY, currentCameraZ)
        view.lookAt(currentCenterX, currentCenterY, currentCenterZ)

        if (transitionPercentage >= 1) this.isTransitioning = false
    }

    saveCameraPosition() {
        const cam = this.#currentCamera
        this.#currentCameraState = {
            x: cam.eyeX,
            y: cam.eyeY,
            z: cam.eyeZ,
            centerX: cam.centerX,
            centerY: cam.centerY,
            centerZ: cam.centerZ
        }
    }

    restoreCameraPosition() {
        this.#currentCamera.setPosition(this.#currentCameraState.x, this.#currentCameraState.y, this.#currentCameraState.z);
        this.#currentCamera.lookAt(this.#currentCameraState.centerX, this.#currentCameraState.centerY, this.#currentCameraState.centerZ);
    }

    onMouseWheel(event) {
        const className = event.target.className;
        if (className !== 'p5Canvas') return true;
        const center = { x: this.#currentCamera.centerX, y: this.#currentCamera.centerY, z: this.#currentCamera.centerZ };
        const radioBefore = this.#radio;
        const zoomFactor = constrain(norm(this.#radio, CameraManager.MIN_RADIO, CameraManager.MAX_RADIO), 0.1, 1);
        (event.deltaY > 0) ?
            this.#radio = min(round(this.#radio + CameraManager.ZOOM_INCREMENT * zoomFactor), CameraManager.MAX_RADIO) :
            this.#radio = max(round(this.#radio - CameraManager.ZOOM_INCREMENT * zoomFactor), CameraManager.MIN_RADIO);
        const distance = this.#radio - radioBefore;
        this.#currentCamera.move(0, 0, distance);
        this.#currentCamera.lookAt(center.x, center.y, center.z);
        return false
    }

    onMouseDragged(event) {
        // Check if shift is pressed or right mouse button
        if (event.shiftKey || mouseButton === RIGHT) {
            console.log(event.shiftKey);
            const panXAmount = movedX * 100 / width * -3;
            const panYAmount = movedY * 100 / height * -3;
            this.#currentCamera.move(panXAmount, panYAmount, 0);
        } else if (mouseButton === LEFT) {
            orbitControl(5, 5, 0);
        }
    }

    onDraw = () => {
        this.animateTransition();
    }
}

// p5.js functions
function preload() {
    if (typeof modelFile === 'undefined') return;
    downloadedModel = loadModel(targetDir + modelFile, true);
}

function setup() {
    viewer = new Viewer();
    noStroke();
    setupListeners();
}

function draw() {
    viewer.onDraw();
}

function mouseDragged(event) {
    if (event.target.localName !== 'canvas') return true
    viewer.onMouseDragged(event);
    return false;
}

function mouseWheel(event) {
    return viewer.onMouseWheel(event);
}

function windowResized(event) {
    viewer.onResizeCanvas(event);
}

function keyPressed() {
    // If F key is pressed...
    if (keyCode === 70) {
        viewer.goFullscreen();
        return false;
    }
    // If 0 key is pressed...
    if (keyCode === 96 || keyCode === 48) {
        viewer.recenterCamera();
    }
    return true;
}

// Setup listeners
function setupListeners() {
    document.querySelector('div#controls input#backgroundColor').addEventListener('input', viewer.cambiarColorDeFondo);
    // document.querySelector('div#controls input#ambientLightsColor').addEventListener('input', viewer.cambiarAmbientLightsColor);
    document.querySelector('div#controls input#meshColor').addEventListener('input', viewer.cambiarStrokeColor);

    document.querySelector('div#controls input#mostrarMeshSwitch').addEventListener('change', viewer.toggleMostrarMesh);
    document.querySelector('div#controls input#useAmbientLights').addEventListener('change', viewer.toggleAmbientLights);

    document.querySelector('div#controls div.form-check input#ambientRender').addEventListener('change', viewer.cambiarModoRenderizado);
    document.querySelector('div#controls div.form-check input#specularRender').addEventListener('change', viewer.cambiarModoRenderizado);
    document.querySelector('div#controls div.form-check input#normalRender').addEventListener('change', viewer.cambiarModoRenderizado);

    document.querySelector('div#controls input#materialColor').addEventListener('input', viewer.cambiarMaterialColor);

    document.querySelector('div#controls input#brillantez').addEventListener('input', viewer.cambiarBrillantez)

    document.querySelector('div#controls input[type="checkbox"]#invertirAxisY').addEventListener('change', viewer.toggleFlipZAxis);

    document.querySelector('button#guardarCapturaButton').addEventListener('click', (e) => {
        const fileName = `Visor3D_` + new Date().toLocaleString().toString().replace(' ', '_').replace(',', '');
        const format = 'png';
        saveCanvas(fileName, format);
    })

    document.querySelector('input[type="range"]#escalarModelo')?.addEventListener('input', viewer.escalarModelo);
}

// Funcion para animar la interpolacion
function easeOut(x) {
    return 1 - Math.pow(1 - x, 4);
}
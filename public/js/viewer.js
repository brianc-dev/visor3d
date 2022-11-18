// autor Brian Caldera 2022

let viewer, fileManager;
let defaultBehavior = false;

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
    #background;
    get isFullscreenMode() { return document.fullscreenElement }
    constructor() {
        this.#background = new Background();
    }
    setup() {
        angleMode(DEGREES);
        let parent = document.getElementById(CANVAS_HOLDER);
        this.#canvas = createCanvas(parent.clientWidth, parent.clientHeight, WEBGL);
        this.#canvas.parent(CANVAS_HOLDER);

        this.#cameraManager = new CameraManager();
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
        this.#cameraManager.onMouseDragged(event);
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

    draw() {
        background(this.#background.color);
        box();
    }
}

class Background {
    color;
    constructor(hex = DEFAULT_BG_COLOR) {
        this.color = color(hex);
    }
}

class CameraManager {
    static get MIN_RADIO() { return 50 }
    static get MAX_RADIO() { return 1000 }
    static get ZOOM_INCREMENT() { return 60 }
    #angle = 45;
    #radio = 100;
    #currentCamera;
    #currentCameraState;
    #cameras = [];
    constructor() {
        const newCamera = createCamera();
        this.#currentCamera = newCamera;
        this.#cameras.push(newCamera);
        newCamera.setPosition(0, 0, this.#radio);
        newCamera.lookAt(0, 0, 0);
        setCamera(newCamera);
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
        orbitControl(5, 5, 5);
    }
}

class FileManager {
    loadFile(file) {
        try {
            loadModel(file,
                 true,
                 (model) => {

                 }, 
                 () => {

                 })
        } catch (error) {
            
        }
    }
}

class Mesh {
    #mesh;
    constructor() {

    }
    render() {

    }
}

// p5.js functions
function preload() {

}

function setup() {
    viewer = new Viewer();
    viewer.setup();
    fileManager = new FileManager();
}

function draw() {
    viewer.draw();
}

function mouseDragged(event) {
    viewer.onMouseDragged(event);
    return defaultBehavior;
}

function mouseWheel(event) {
    return viewer.onMouseWheel(event);
}

function windowResized(event) {
    viewer.onResizeCanvas(event);
}

function keyPressed() {
    if (keyCode === 70) {
        viewer.goFullscreen();
        return false;
    }
    return true;
}

// function mousePressed(event) {
//     if (mouseButton === LEFT) {
//         requestPointerLock();
//     }
// }

// function mouseReleased(event) {
//     exitPointerLock();
// }

function setupListeners() {

}

function easeOut(x) {
    return 1 - Math.pow(1 - x, 4);
}

function onDropHandler(event) {
    event.preventDefault();
    console.log('file dropped');
    if (event.dataTransfer.items) {
        // Use DataTransferItemList interface to access the file(s)
        [...event.dataTransfer.items].forEach((item, i) => {
          // If dropped items aren't files, reject them
          if (item.kind === 'file') {
            const file = item.getAsFile();
            console.log(`… file[${i}].name = ${file.name}`);
          }
        });
      } else {
        // Use DataTransfer interface to access the file(s)
        [...event.dataTransfer.files].forEach((file, i) => {
          console.log(`… file[${i}].name = ${file.name}`);
        });
      }
}

function onDragOverHandler(event) {
    event.preventDefault();
}
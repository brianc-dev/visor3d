<div id="controls" class="p-4 text-white fw-bold user-select-none">
    <div class="row"><h4 class="fw-bold">Controles</h4></div>
    <fieldset>
        <legend class="controls__header-text">Opciones de ambiente</legend>
        <div>
            <label for="backgroundColor" class="form-label">Color de fondo</label>
            <input id="backgroundColor" type="color" class="form-control form-control-color" value="#000000"
                title="Elige el color de fondo">
        </div>
        <div class="form-check form-switch py-2">
            <input class="form-check-input" type="checkbox" id="useAmbientLights">
            <label class="form-check-label" for="useAmbientLights">Usar luz ambiental</label>
        </div>
    </fieldset>

    <fieldset>
        <legend class="controls__header-text">Opciones de modelo</legend>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="mostrarMeshSwitch">
            <label class="form-check-label" for="mostrarMeshSwitch">Mostrar mesh</label>
        </div>
        <div>
            <label for="meshColor" class="form-label">Color de mesh</label>
            <input id="meshColor" type="color" class="form-control form-control-color" value="#000000"
                title="Elige el color del mesh">
        </div>
        <div class="form-check form-switch py-2">
            <input class="form-check-input" type="checkbox" role="switch" id="invertirAxisY">
            <label class="form-check-label" for="invertirAxisY">Invertir axis Y</label>
        </div>
        <div>
            <label for="escalarModelo" class="pt-2 form-label">Escalar modelo</label>
            <input type="range" id="escalarModelo" max="10" min="0.1" step="0.1" value="1">
        </div>
    </fieldset>
    <fieldset>
        <legend class="controls__header-text">Opciones de material</legend>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="renderMode" id="ambientRender" value="1" checked>
            <label class="form-check-label" for="ambientRender">Ambiente</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="renderMode" id="specularRender" value="2">
            <label class="form-check-label" for="specularRender">Especular</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="renderMode" id="normalRender" value="3">
            <label class="form-check-label" for="normalRender">Normal</label>
        </div>
        <div>
            <label for="materialColor" class="pt-2 form-label">Color del material</label>
            <input id="materialColor" type="color" class="form-control form-control-color" value="#DBDBDB"
                title="Elige el color de fondo">
        </div>
        <div>
            <label for="brillantez" class=" pt-2 form-label">Brillantez <small>(Solo en modo especular)</small></label>
            <input type="range" class="form-range" id="brillantez" min="0.1" max="100" step="0.1" value="20">
        </div>
    </fieldset>
    <button id="guardarCapturaButton" class="btn btn-primary btn-sm rounded-pill">Guardar captura</button>
</div>
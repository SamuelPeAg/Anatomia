<div id="modal-confirm" class="modal-backdrop oculto">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#f59e0b">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 id="modal-confirm-title" class="modal-title">Cambios sin guardar</h3>
        </div>
        
        <div class="modal-body">
            <p id="modal-confirm-msg" class="modal-message">
                Tienes cambios sin guardar en esta fase. Si cambias ahora, podrías perder los datos no guardados.
            </p>
        </div>
        
        <div class="modal-footer">
            <button id="modal-btn-cancel" class="boton boton-fantasma">Seguir editando</button>
            <button id="modal-btn-confirm" class="boton boton-peligro">Continuar sin guardar</button>
        </div>
    </div>
</div>

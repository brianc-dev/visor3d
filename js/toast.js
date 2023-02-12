let toastContainer;

/**
 * Convenience function used to create toasts from javascript
 * @param {string} message the message to display
 * @param {string} type the type of the toast
 */
function showToast(message, type = 'success') {
  const toastTemplate = formatToast(message, type);
  toastContainer.appendChild(toastTemplate);

  const toast = new bootstrap.Toast(toastTemplate);

  toastTemplate.addEventListener('hidden.bs.toast', () => {
    toastContainer.removeChild(toastTemplate);
  });

  toast.show();
}

/**
 * Formats a toast and return it
 * @param {string} message the message to display
 * @param {string} type the type of the toast
 * @returns {HTMLElement} the toast ready to be appended
 */
function formatToast(message, type) {
  const toastInner = `
  <div class="toast-header">
    <strong class="me-auto">Visor3D</strong>
    <small>${new Date().toLocaleString()}</small>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
  <div class="toast-body">
    ${message}
  </div>`;

  const toast = document.createElement('div');
  toast.classList.add('toast');
  toast.classList.add(`text-bg-${type}`);
  toast.setAttribute('role', 'alert');
  toast.setAttribute('aria-live', 'assertive');
  toast.setAttribute('aria-atomic', 'true');
  toast.innerHTML = toastInner;

  return toast;
}

/**
 * Check for toast ready to be displayed loaded from server
 */
function checkToast() {
  const toastDate = new Date().toLocaleString();
  // Check for toast here
  
  const toasts = Array.from(toastContainer.children);
  toasts.forEach(toastTemplate => {
    Array.from(toastTemplate.getElementsByClassName('toast-date')).forEach(toastDateContainer => {
      toastDateContainer.textContent = toastDate;
    })
    const toast = new bootstrap.Toast(toastTemplate);
    toastTemplate.addEventListener('hidden.bs.toast', () => {
      toastContainer.removeChild(toastTemplate);
    });
    toast.show();
  });
}

window.addEventListener('load', () => {
  toastContainer = document.querySelector('div.toast-container');
  checkToast();
})
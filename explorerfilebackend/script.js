document.querySelectorAll('.draggable').forEach((windowEl, index) => {
  const titleBar = windowEl.querySelector('.title-bar');
  const minimizeBtn = titleBar.querySelector('button[aria-label="Minimize"]');
  const maximizeBtn = titleBar.querySelector('button[aria-label="Maximize"]');
  const content = windowEl.querySelector('.window-body');

  const windowId = `window-${index}`;
  let isDragging = false, offsetX, offsetY;
  let isMaximized = false;
  let originalStyle = {};

  // Charger état depuis localStorage
  const saved = JSON.parse(localStorage.getItem(windowId));
  if (saved) {
    if (saved.isMaximized) {
      maximizeWindow();
    } else {
      windowEl.style.left = saved.left;
      windowEl.style.top = saved.top;
      windowEl.style.position = "absolute";
    }
  }

  titleBar.style.cursor = "move";

  titleBar.addEventListener('mousedown', (e) => {
    if (isMaximized) return;

    isDragging = true;
    const rect = windowEl.getBoundingClientRect();
    offsetX = e.clientX - rect.left;
    offsetY = e.clientY - rect.top;

    windowEl.style.position = "absolute";
    windowEl.style.zIndex = 1000;
  });

  document.addEventListener('mousemove', (e) => {
    if (isDragging && !isMaximized) {
      const left = `${e.clientX - offsetX}px`;
      const top = `${e.clientY - offsetY}px`;

      windowEl.style.left = left;
      windowEl.style.top = top;

      // Enregistrer la position si pas maximisé
      localStorage.setItem(windowId, JSON.stringify({
        left,
        top,
        isMaximized: false
      }));
    }
  });

  document.addEventListener('mouseup', () => {
    isDragging = false;
  });

  // Minimize
  if (minimizeBtn && content) {
    minimizeBtn.addEventListener('click', () => {
      content.style.display = content.style.display === 'none' ? 'flex' : 'none';
    });
  }

  // Maximize
  if (maximizeBtn) {
    maximizeBtn.addEventListener('click', () => {
      if (!isMaximized) {
        maximizeWindow();
      } else {
        restoreWindow();
      }
    });
  }

  function maximizeWindow() {
    originalStyle = {
      left: windowEl.style.left,
      top: windowEl.style.top,
      width: windowEl.style.width,
      height: windowEl.style.height,
      position: windowEl.style.position,
    };

    windowEl.style.left = "0";
    windowEl.style.top = "0";
    windowEl.style.width = "100vw";
    windowEl.style.height = "100vh";
    windowEl.style.position = "fixed";
    windowEl.style.margin = "0";
    isMaximized = true;

    localStorage.setItem(windowId, JSON.stringify({
      isMaximized: true
    }));
  }

  function restoreWindow() {
    Object.entries(originalStyle).forEach(([key, value]) => {
      windowEl.style[key] = value;
    });
    isMaximized = false;

    localStorage.setItem(windowId, JSON.stringify({
      left: originalStyle.left,
      top: originalStyle.top,
      isMaximized: false
    }));
  }
});

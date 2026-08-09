// Wiederverwendbare Fokuspunkt-Komponente (Epic #164): Klick auf ein Bild
// setzt eine x/y-Koordinate in Prozent, zeigt einen Marker an und ruft
// onChange(x, y) auf. Wird für Hero-Bild (#166) und Block-Bild (#167) genutzt.
function initFocusPoint(container, img, initialX, initialY, onChange) {
  function clampPercent(value) {
    if (typeof value !== 'number' || isNaN(value)) {
      return 50;
    }
    return Math.min(100, Math.max(0, value));
  }

  container.classList.add('focus-point-container');

  var marker = document.createElement('div');
  marker.className = 'focus-point-marker';
  container.appendChild(marker);

  var x = clampPercent(parseFloat(initialX));
  var y = clampPercent(parseFloat(initialY));

  function place(px, py) {
    x = clampPercent(px);
    y = clampPercent(py);
    marker.style.left = x + '%';
    marker.style.top = y + '%';
    img.style.objectPosition = x + '% ' + y + '%';
  }

  place(x, y);

  container.addEventListener('click', function (e) {
    var rect = img.getBoundingClientRect();
    var px = ((e.clientX - rect.left) / rect.width) * 100;
    var py = ((e.clientY - rect.top) / rect.height) * 100;
    place(px, py);
    if (typeof onChange === 'function') {
      onChange(Math.round(x * 100) / 100, Math.round(y * 100) / 100);
    }
  });

  return {
    setPosition: place,
    getPosition: function () {
      return { x: x, y: y };
    }
  };
}

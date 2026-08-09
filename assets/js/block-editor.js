(function () {
  var BLOCK_LABELS = {
    paragraph: 'Text',
    heading: 'Überschrift',
    quote: 'Zitat',
    list: 'Liste',
    image: 'Bild'
  };

  var DEFAULT_ALLOWED_TYPES = ['paragraph', 'heading', 'quote', 'list', 'image'];
  var UPLOAD_URL = '/admin/upload-block-image.php';

  // Must stay in sync with BLOCK_COLOR_OPTIONS / BLOCK_FONT_SIZE_OPTIONS in
  // includes/blocks.php - keys map 1:1 to the --color-{key} / --font-size-{key}
  // CSS custom properties in assets/css/variables.css.
  var BLOCK_COLOR_OPTIONS = {
    'primary': 'Dunkelolive (Haupttext)',
    'primary-dark': 'Fast-Schwarzoliv',
    'accent-red': 'Rot (Akzent)',
    'accent-rust': 'Rostrot/Terrakotta',
    'secondary-gold': 'Olivgold',
    'secondary-khaki': 'Olivkhaki',
    'neutral-cream': 'Creme',
    'neutral-tan': 'Beige/Tan',
    'neutral-blue-light': 'Blaugrau hell',
    'neutral-blue': 'Blaugrau',
    'accent-mauve-dark': 'Mauve dunkel',
    'accent-mauve': 'Mauve/Beere'
  };
  var BLOCK_FONT_SIZE_OPTIONS = {
    'text': 'Fliesstext (14px)',
    'zitat': 'Zitat (16px)',
    'abschnitt-kopfzeile': 'Abschnitt-Kopfzeile (18px)',
    'zwischenueberschrift': 'Zwischenüberschrift (24px)',
    'ueberschrift': 'Überschrift (32px)',
    'untertitel': 'Untertitel (32px)',
    'titel': 'Titel (42px)'
  };
  var STYLEABLE_TYPES = ['paragraph', 'heading', 'quote', 'list'];

  var nextLocalId = 1;

  function makeBlock(type) {
    var block = { id: 'b' + (nextLocalId++), type: type };
    if (type === 'list') {
      block.items = [''];
    } else if (type === 'image') {
      block.src = '';
      block.alt = '';
    } else {
      block.content = '';
    }
    if (STYLEABLE_TYPES.indexOf(type) !== -1) {
      block.color = '';
      block.fontSize = '';
    }
    return block;
  }

  function normalizeBlock(raw) {
    var type = raw && raw.type;
    if (!BLOCK_LABELS[type]) {
      type = 'paragraph';
    }
    var block = { id: 'b' + (nextLocalId++), type: type };
    if (type === 'list') {
      block.items = Array.isArray(raw.items) && raw.items.length > 0 ? raw.items.slice() : [''];
    } else if (type === 'image') {
      block.src = typeof raw.src === 'string' ? raw.src : '';
      block.alt = typeof raw.alt === 'string' ? raw.alt : '';
    } else {
      block.content = typeof raw.content === 'string' ? raw.content : '';
    }
    if (STYLEABLE_TYPES.indexOf(type) !== -1) {
      block.color = (typeof raw.color === 'string' && BLOCK_COLOR_OPTIONS[raw.color]) ? raw.color : '';
      block.fontSize = (typeof raw.fontSize === 'string' && BLOCK_FONT_SIZE_OPTIONS[raw.fontSize]) ? raw.fontSize : '';
    }
    return block;
  }

  function parseInitialBlocks(raw) {
    var trimmed = (raw || '').trim();
    if (trimmed === '') {
      return [makeBlock('paragraph')];
    }
    if (trimmed.charAt(0) === '[') {
      try {
        var parsed = JSON.parse(trimmed);
        if (Array.isArray(parsed) && parsed.length > 0) {
          return parsed.map(normalizeBlock);
        }
      } catch (e) {
        // Not valid JSON - fall through to legacy handling below.
      }
    }
    // Legacy format (plain HTML string from before the block editor): keep it
    // as a single text block instead of discarding it.
    var legacy = makeBlock('paragraph');
    legacy.content = trimmed;
    return [legacy];
  }

  function serializeBlocks(blocks) {
    return blocks.map(function (block) {
      var out;
      if (block.type === 'list') {
        out = { type: 'list', items: block.items };
      } else if (block.type === 'image') {
        return { type: 'image', src: block.src, alt: block.alt };
      } else {
        out = { type: block.type, content: block.content };
      }
      if (block.color) {
        out.color = block.color;
      }
      if (block.fontSize) {
        out.fontSize = block.fontSize;
      }
      return out;
    });
  }

  function createIconButton(label, title, onClick) {
    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'block-editor-icon-btn';
    button.textContent = label;
    button.title = title;
    // Prevent the button from stealing focus/selection away from a
    // contenteditable block before a formatting command (execCommand) runs.
    button.addEventListener('mousedown', function (e) {
      e.preventDefault();
    });
    button.addEventListener('click', onClick);
    return button;
  }

  window.initBlockEditor = function (fieldId, options) {
    var textarea = document.getElementById(fieldId);
    if (!textarea) {
      return;
    }

    var allowedTypes = (options && options.allowedTypes) || DEFAULT_ALLOWED_TYPES;
    textarea.classList.add('wysiwyg-hidden-source');

    var container = document.createElement('div');
    container.className = 'block-editor';
    textarea.parentNode.insertBefore(container, textarea);

    var blocks = parseInitialBlocks(textarea.value);

    function sync() {
      textarea.value = JSON.stringify(serializeBlocks(blocks));
      textarea.dispatchEvent(new Event('input'));
    }

    function render() {
      container.innerHTML = '';
      container.appendChild(createInserter(0));
      blocks.forEach(function (block, index) {
        container.appendChild(renderBlock(block, index));
        container.appendChild(createInserter(index + 1));
      });
      sync();
    }

    function createInserter(index) {
      var wrap = document.createElement('div');
      wrap.className = 'block-editor-inserter';

      var toggle = createIconButton('+', 'Block einfügen', function () {
        var open = menu.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
      toggle.setAttribute('aria-expanded', 'false');

      var menu = document.createElement('div');
      menu.className = 'block-editor-insert-menu';
      allowedTypes.forEach(function (type) {
        var option = document.createElement('button');
        option.type = 'button';
        option.className = 'block-editor-insert-option';
        option.textContent = BLOCK_LABELS[type] || type;
        option.addEventListener('click', function () {
          blocks.splice(index, 0, makeBlock(type));
          render();
        });
        menu.appendChild(option);
      });

      wrap.appendChild(toggle);
      wrap.appendChild(menu);
      return wrap;
    }

    function renderBlock(block, index) {
      var wrap = document.createElement('div');
      wrap.className = 'block-editor-block';

      var header = document.createElement('div');
      header.className = 'block-editor-block-header';

      var badge = document.createElement('span');
      badge.className = 'block-editor-badge';
      badge.textContent = BLOCK_LABELS[block.type] || block.type;
      header.appendChild(badge);

      var controls = document.createElement('div');
      controls.className = 'block-editor-controls';
      controls.appendChild(createIconButton('↑', 'Nach oben verschieben', function () {
        if (index > 0) {
          var tmp = blocks[index - 1];
          blocks[index - 1] = blocks[index];
          blocks[index] = tmp;
          render();
        }
      }));
      controls.appendChild(createIconButton('↓', 'Nach unten verschieben', function () {
        if (index < blocks.length - 1) {
          var tmp = blocks[index + 1];
          blocks[index + 1] = blocks[index];
          blocks[index] = tmp;
          render();
        }
      }));
      controls.appendChild(createIconButton('✕', 'Block löschen', function () {
        if (!window.confirm('Diesen Block wirklich löschen?')) {
          return;
        }
        blocks.splice(index, 1);
        if (blocks.length === 0) {
          blocks.push(makeBlock('paragraph'));
        }
        render();
      }));
      header.appendChild(controls);

      wrap.appendChild(header);
      wrap.appendChild(renderBlockBody(block));

      if (STYLEABLE_TYPES.indexOf(block.type) !== -1) {
        wrap.appendChild(renderStyleControls(block, wrap));
        applyBlockStyle(wrap.querySelector('.block-editor-field'), block);
      }

      return wrap;
    }

    function applyBlockStyle(el, block) {
      if (!el) {
        return;
      }
      el.style.color = block.color ? 'var(--color-' + block.color + ')' : '';
      el.style.fontSize = block.fontSize ? 'var(--font-size-' + block.fontSize + ')' : '';
    }

    function renderStyleControls(block, wrap) {
      var row = document.createElement('div');
      row.className = 'block-editor-style-controls';

      var colorSelect = document.createElement('select');
      colorSelect.className = 'block-editor-style-select';
      var colorDefault = document.createElement('option');
      colorDefault.value = '';
      colorDefault.textContent = 'Farbe (Standard)';
      colorSelect.appendChild(colorDefault);
      Object.keys(BLOCK_COLOR_OPTIONS).forEach(function (key) {
        var option = document.createElement('option');
        option.value = key;
        option.textContent = BLOCK_COLOR_OPTIONS[key];
        option.selected = block.color === key;
        colorSelect.appendChild(option);
      });
      colorSelect.addEventListener('change', function (e) {
        block.color = e.target.value;
        applyBlockStyle(wrap.querySelector('.block-editor-field'), block);
        sync();
      });

      var sizeSelect = document.createElement('select');
      sizeSelect.className = 'block-editor-style-select';
      var sizeDefault = document.createElement('option');
      sizeDefault.value = '';
      sizeDefault.textContent = 'Grösse (Standard)';
      sizeSelect.appendChild(sizeDefault);
      Object.keys(BLOCK_FONT_SIZE_OPTIONS).forEach(function (key) {
        var option = document.createElement('option');
        option.value = key;
        option.textContent = BLOCK_FONT_SIZE_OPTIONS[key];
        option.selected = block.fontSize === key;
        sizeSelect.appendChild(option);
      });
      sizeSelect.addEventListener('change', function (e) {
        block.fontSize = e.target.value;
        applyBlockStyle(wrap.querySelector('.block-editor-field'), block);
        sync();
      });

      row.appendChild(colorSelect);
      row.appendChild(sizeSelect);
      return row;
    }

    function renderBlockBody(block) {
      if (block.type === 'list') {
        return renderListBody(block);
      }
      if (block.type === 'heading') {
        return renderHeadingBody(block);
      }
      if (block.type === 'image') {
        return renderImageBody(block);
      }
      return renderRichBody(block);
    }

    function renderRichBody(block) {
      var wrapper = document.createElement('div');

      var toolbar = document.createElement('div');
      toolbar.className = 'block-editor-mini-toolbar';
      toolbar.appendChild(createIconButton('F', 'Fett', function () {
        document.execCommand('bold');
        editor.focus();
      }));
      toolbar.appendChild(createIconButton('K', 'Kursiv', function () {
        document.execCommand('italic');
        editor.focus();
      }));
      toolbar.appendChild(createIconButton('Link', 'Link einfügen', function () {
        var url = window.prompt('Link-Ziel (URL):', 'https://');
        if (url) {
          document.execCommand('createLink', false, url);
        }
        editor.focus();
      }));

      var editor = document.createElement('div');
      editor.className = block.type === 'quote' ? 'block-editor-field block-editor-quote' : 'block-editor-field';
      editor.contentEditable = 'true';
      editor.innerHTML = block.content;
      editor.addEventListener('input', function () {
        block.content = editor.innerHTML;
        sync();
      });

      wrapper.appendChild(toolbar);
      wrapper.appendChild(editor);
      return wrapper;
    }

    function renderHeadingBody(block) {
      var input = document.createElement('input');
      input.type = 'text';
      input.className = 'block-editor-field block-editor-heading-field';
      input.placeholder = 'Überschrift';
      input.value = block.content;
      input.addEventListener('input', function () {
        block.content = input.value;
        sync();
      });
      return input;
    }

    function renderImageBody(block) {
      var wrapper = document.createElement('div');
      wrapper.className = 'block-editor-image-field';

      var preview = document.createElement('img');
      preview.className = 'block-editor-image-preview';
      if (block.src) {
        preview.src = '/uploads/' + block.src;
      } else {
        preview.style.display = 'none';
      }

      var fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.accept = 'image/jpeg,image/png,image/webp';

      var status = document.createElement('p');
      status.className = 'block-editor-image-status';

      var altInput = document.createElement('input');
      altInput.type = 'text';
      altInput.className = 'block-editor-field';
      altInput.placeholder = 'Alternativtext (Bildbeschreibung)';
      altInput.value = block.alt;
      altInput.style.display = block.src ? '' : 'none';
      altInput.addEventListener('input', function () {
        block.alt = altInput.value;
        sync();
      });

      fileInput.addEventListener('change', function () {
        var file = fileInput.files[0];
        if (!file) {
          return;
        }
        status.textContent = 'Lädt hoch …';
        var formData = new FormData();
        formData.append('image', file);
        fetch(UPLOAD_URL, { method: 'POST', body: formData, credentials: 'same-origin' })
          .then(function (res) {
            return res.json();
          })
          .then(function (data) {
            if (data.success) {
              block.src = data.filename;
              preview.src = data.url;
              preview.style.display = '';
              altInput.style.display = '';
              status.textContent = '';
              sync();
            } else {
              status.textContent = data.error || 'Upload fehlgeschlagen.';
            }
          })
          .catch(function () {
            status.textContent = 'Upload fehlgeschlagen.';
          });
      });

      wrapper.appendChild(preview);
      wrapper.appendChild(fileInput);
      wrapper.appendChild(status);
      wrapper.appendChild(altInput);
      return wrapper;
    }

    function renderListBody(block) {
      var wrapper = document.createElement('div');
      wrapper.className = 'block-editor-list-field';

      var textarea = document.createElement('textarea');
      textarea.className = 'block-editor-field';
      textarea.placeholder = 'Ein Eintrag pro Zeile';
      textarea.value = block.items.join('\n');
      textarea.addEventListener('input', function () {
        block.items = textarea.value.split('\n');
        sync();
      });

      wrapper.appendChild(textarea);
      return wrapper;
    }

    render();
  };

  function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // Client-side preview rendering only (mirrors includes/blocks.php's
  // renderBlockList() for admin live-preview panels). The authoritative,
  // security-relevant sanitization always happens server-side on save/render.
  window.renderBlocksToHtml = function (raw) {
    var trimmed = (raw || '').trim();
    if (trimmed === '') {
      return '';
    }
    if (trimmed.charAt(0) !== '[') {
      return trimmed;
    }
    var blocks;
    try {
      blocks = JSON.parse(trimmed);
    } catch (e) {
      return trimmed;
    }
    if (!Array.isArray(blocks)) {
      return trimmed;
    }

    return blocks.map(function (block) {
      switch (block.type) {
        case 'heading':
          return '<h3 class="block-heading">' + escapeHtml(block.content || '') + '</h3>';
        case 'quote':
          return '<blockquote class="block-quote">' + (block.content || '') + '</blockquote>';
        case 'image':
          return block.src
            ? '<img src="/uploads/' + escapeHtml(block.src) + '" alt="' + escapeHtml(block.alt || '') + '" class="block-image">'
            : '';
        case 'list':
          return '<ul class="block-list">' + (block.items || []).map(function (item) {
            return '<li>' + escapeHtml(item) + '</li>';
          }).join('') + '</ul>';
        default:
          return '<p class="block-paragraph">' + (block.content || '') + '</p>';
      }
    }).join('');
  };
})();

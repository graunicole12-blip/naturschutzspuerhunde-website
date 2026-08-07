(function () {
  function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function createButton(label, title, onClick) {
    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'wysiwyg-btn';
    button.textContent = label;
    button.title = title;
    button.addEventListener('mousedown', function (e) {
      e.preventDefault();
    });
    button.addEventListener('click', onClick);
    return button;
  }

  window.initWysiwyg = function (fieldId) {
    var textarea = document.getElementById(fieldId);
    if (!textarea) {
      return;
    }

    textarea.classList.add('wysiwyg-hidden-source');

    var toolbar = document.createElement('div');
    toolbar.className = 'wysiwyg-toolbar';

    var editor = document.createElement('div');
    editor.className = 'wysiwyg-editor';
    editor.contentEditable = 'true';
    editor.innerHTML = textarea.value;

    function syncToTextarea() {
      textarea.value = editor.innerHTML;
      textarea.dispatchEvent(new Event('input'));
    }

    toolbar.appendChild(createButton('F', 'Fett', function () {
      document.execCommand('bold');
      editor.focus();
      syncToTextarea();
    }));
    toolbar.appendChild(createButton('K', 'Kursiv', function () {
      document.execCommand('italic');
      editor.focus();
      syncToTextarea();
    }));
    toolbar.appendChild(createButton('•', 'Aufzählung', function () {
      document.execCommand('insertUnorderedList');
      editor.focus();
      syncToTextarea();
    }));
    toolbar.appendChild(createButton('Link', 'Link einfügen', function () {
      var url = window.prompt('Link-Ziel (URL):', 'https://');
      if (url) {
        document.execCommand('createLink', false, url);
      }
      editor.focus();
      syncToTextarea();
    }));
    toolbar.appendChild(createButton('ABC', 'Grossschreibung', function () {
      var selection = window.getSelection();
      var text = selection ? selection.toString() : '';
      if (text) {
        document.execCommand('insertHTML', false, '<span class="text-upper">' + escapeHtml(text) + '</span>');
      }
      editor.focus();
      syncToTextarea();
    }));

    textarea.parentNode.insertBefore(toolbar, textarea);
    textarea.parentNode.insertBefore(editor, textarea);

    editor.addEventListener('input', syncToTextarea);
  };
})();

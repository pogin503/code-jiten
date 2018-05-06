import CodeMirror from 'codemirror';

// CodeMirror
var editor = CodeMirror.fromTextArea(document.getElementById('editor-js'), {
    mode: 'javascript',
    lineNumbers: true,
    // indentUnit: 5
});

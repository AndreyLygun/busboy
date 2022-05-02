<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
        plugins: 'code table lists',
        toolbar: 'undo redo | formatselect| bold italic fontname forecolor fontsize | alignleft aligncenter alignright | indent outdent | bullist numlist | table',
        language: 'ru',
        setup: function (editor) {
            editor.onChange.add(function() {
                editor.save();
            });
        }
    });
</script>

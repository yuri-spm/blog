document.addEventListener('DOMContentLoaded', function () {
    const textareas = document.querySelectorAll('.autoResizeTextarea');

    function autoResize(event) {
        const textarea = event.target;
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    textareas.forEach(textarea => {
        textarea.addEventListener('input', autoResize);
        // Ajusta o tamanho inicial ao carregar a página se houver conteúdo pré-carregado
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    });
});

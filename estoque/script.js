document.addEventListener('DOMContentLoaded', function() {

    /**
     * Funcionalidade para os filtros de categoria.
     * Ao clicar em uma tag, ela se torna ativa e as outras perdem o estado ativo.
     */
    const filterTags = document.querySelectorAll('.filter-tags .tag');

    filterTags.forEach(tag => {
        tag.addEventListener('click', () => {
            // Remove a classe 'active' de todas as tags
            filterTags.forEach(t => t.classList.remove('active'));
            // Adiciona a classe 'active' apenas na tag clicada
            tag.classList.add('active');
            
            // Em uma aplicação real, aqui você filtraria os produtos
            console.log(`Filtro de categoria alterado para: ${tag.innerText}`);
        });
    });


    /**
     * Funcionalidade para o botão de Modo Escuro.
     * Adiciona ou remove a classe 'dark-mode' do elemento <body>
     * para que os estilos CSS possam ser aplicados.
     */
    const darkModeToggle = document.getElementById('darkModeToggle');

    darkModeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');

        // Opcional: Altera o ícone e o texto do botão
        const icon = darkModeToggle.querySelector('i');
        const text = darkModeToggle.querySelector('span');

        if (document.body.classList.contains('dark-mode')) {
            icon.classList.remove('bx-moon');
            icon.classList.add('bx-sun');
            text.innerText = 'Modo Claro';
        } else {
            icon.classList.remove('bx-sun');
            icon.classList.add('bx-moon');
            text.innerText = 'Modo Escuro';
        }
    });

});
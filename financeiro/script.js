// --- Interatividade para o Painel ---

document.addEventListener('DOMContentLoaded', function() {

    /**
     * Funcionalidade para os botões de filtro do gráfico (Weekly, Monthly, Yearly).
     * Quando um botão é clicado, ele se torna ativo e os outros são desativados.
     * Isso permite que o CSS aplique o estilo correto ao botão ativo.
     */
    const toggleButtons = document.querySelectorAll('.graph-toggles .toggle-btn');

    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            // 1. Remove a classe 'active' de todos os botões do grupo
            toggleButtons.forEach(btn => btn.classList.remove('active'));

            // 2. Adiciona a classe 'active' apenas ao botão que foi clicado
            button.classList.add('active');

            // Em uma aplicação real, aqui você chamaria uma função para
            // atualizar os dados do gráfico com base no período selecionado.
            // Por exemplo: updateChartData(button.textContent);
            console.log(`Período do gráfico alterado para: ${button.textContent}`);
        });
    });

    /**
     * Nota:
     * A lógica para desenhar o gráfico em si não está incluída aqui,
     * pois é uma tarefa complexa que geralmente requer uma biblioteca de terceiros
     * como Chart.js, D3.js, ou ApexCharts.
     * Este código foca na recriação da interface e sua interatividade básica.
     */

});
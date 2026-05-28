<?php
function gerar_slug($texto) {
    $slug = strtolower(trim($texto));
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}

function tempo_leitura($conteudo) {
    $palavras = str_word_count(strip_tags($conteudo));
    $minutos = max(1, ceil($palavras / 200));
    return $minutos . ' min de leitura';
}

function formatar_data($data) {
    if (!$data) return '';
    $meses = [
        'January' => 'Janeiro', 'February' => 'Fevereiro', 'March' => 'Março',
        'April' => 'Abril', 'May' => 'Maio', 'June' => 'Junho',
        'July' => 'Julho', 'August' => 'Agosto', 'September' => 'Setembro',
        'October' => 'Outubro', 'November' => 'Novembro', 'December' => 'Dezembro'
    ];
    $ts = strtotime($data);
    $mes = $meses[date('F', $ts)] ?? date('F', $ts);
    return date('d', $ts) . ' de ' . $mes . ' de ' . date('Y', $ts);
}

function exibir_status($status) {
    $classes = [
        'rascunho' => ['label' => 'Rascunho', 'cor' => '#EAB308'],
        'pendente' => ['label' => 'Pendente', 'cor' => '#64748B'],
        'publicado' => ['label' => 'Publicado', 'cor' => '#22C55E'],
        'arquivado' => ['label' => 'Arquivado', 'cor' => '#64748B'],
    ];
    $s = $classes[$status] ?? $classes['rascunho'];
    return '<span style="display:inline-block;background:' . $s['cor'] . '22;color:' . $s['cor'] . ';padding:2px 8px;border-radius:4px;font-size:0.8125rem;font-weight:500">' . $s['label'] . '</span>';
}

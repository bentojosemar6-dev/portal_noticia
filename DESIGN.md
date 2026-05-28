---
name: Portal de Notícias IPIL
description: Portal institucional do Instituto Politécnico Industrial de Luanda
colors:
  primary: "#F97316"
  primary-dark: "#EA580C"
  primary-light: "#FED7AA"
  bg: "#FFFFFF"
  bg-soft: "#FFF7ED"
  surface: "#F8FAFC"
  border: "#E2E8F0"
  text: "#1E293B"
  text-muted: "#64748B"
  danger: "#EF4444"
  success: "#22C55E"
  warning: "#EAB308"
typography:
  display:
    fontFamily: "Inter, system-ui, -apple-system, sans-serif"
    fontSize: "clamp(2rem, 5vw, 3.5rem)"
    fontWeight: 800
    lineHeight: 1.1
    letterSpacing: "-0.02em"
  headline:
    fontFamily: "Inter, system-ui, -apple-system, sans-serif"
    fontSize: "clamp(1.5rem, 3vw, 2.25rem)"
    fontWeight: 700
    lineHeight: 1.2
    letterSpacing: "-0.01em"
  title:
    fontFamily: "Inter, system-ui, -apple-system, sans-serif"
    fontSize: "clamp(1.125rem, 2vw, 1.5rem)"
    fontWeight: 600
    lineHeight: 1.3
  body:
    fontFamily: "Inter, system-ui, -apple-system, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "Inter, system-ui, -apple-system, sans-serif"
    fontSize: "0.8125rem"
    fontWeight: 500
    lineHeight: 1.4
    letterSpacing: "0.02em"
rounded:
  sm: "4px"
  md: "8px"
  lg: "12px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
  xxl: "48px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#FFFFFF"
    rounded: "{rounded.md}"
    padding: "12px 24px"
  button-primary-hover:
    backgroundColor: "{colors.primary-dark}"
    textColor: "#FFFFFF"
    rounded: "{rounded.md}"
    padding: "12px 24px"
  button-ghost:
    backgroundColor: "transparent"
    textColor: "{colors.primary}"
    rounded: "{rounded.md}"
    padding: "12px 24px"
---

# Design System: Portal de Notícias IPIL

## 1. Overview

**Creative North Star: "O Farol Acadêmico"**

O Portal de Notícias do IPIL é um farol digital para a comunidade acadêmica. Como um farol, ele precisa ser visível de longe, confiável na orientação que oferece, e constante na sua presença. O design existe para servir o conteúdo institucional com clareza e convicção, eliminando todo ruído visual que separe o visitante da informação que busca.

A paleta laranja [#F97316] funciona como a luz do farol aplicada com precisão cirúrgica. O fundo predominantemente claro [#FFFFFF, #F8FAFC] com tipografia escura [#1E293B] segue a tradição de portais de notícias modernos (The Guardian, Folha), priorizando legibilidade e confiança. Este sistema rejeita explicitamente o sensacionalismo visual cores berrantes desnecessárias, poluição de elementos, e hierarquia tipográfica achatada.

**Key Characteristics:**
- Conteúdo primeiro: hierarquia tipográfica forte, espaçamento generoso, zero decoração supérflua
- Farol cromático: laranja usado como guia (links, badges, destaques), nunca como poluição de fundo
- Planar com propósito: superfícies limpas, elevação sutil apenas para interação
- Jovem sem ser infantil: energia acadêmica com a seriedade que uma instituição de ensino exige
- Consistente do público ao admin: mesmo sistema de cores e tipografia nas duas faces

## 2. Colors

A paleta é organizada em torno do laranja institucional como voz principal, neutros quentes como base silenciosa, e cores funcionais para estados.

**Named Rule — A Regra do Farol.** O laranja primário ocupa no máximo 30% de qualquer tela. Ele guia, não domina. Sua função é apontar para o que importa (links, badges de categoria, botões primários, elemento em destaque), não decorar o que não precisa de atenção.

### Primary

- **Laranja Institucional** (#F97316): Cor vocal do IPIL. Usada em links, badges de categoria, botões primários, borda inferior da navbar, e no hover de elementos interativos. Nunca utilizada como cor de fundo de página ou卡片 (cards) — apenas em superfícies pequenas e intencionais.
- **Laranja Farol** (#EA580C): Tom escurecido para hover states, navbar ativa, e elementos que precisam de contraste extra contra o laranja padrão.
- **Laranja Suave** (#FED7AA): Tom claro para backgrounds de badges, tags, alertas informativos. Aparece em fundo suave para não competir com conteúdo.

### Neutral

- **Branco Giz** (#FFFFFF): Fundo geral da página. Nunca é um branco puro esterilizado é o branco do papel institucional limpo.
- **Branco Papel** (#FFF7ED): Fundo de seções alternadas. Leve toque de laranja (saturação mínima) para conectar visualmente com a marca sem usar a cor vocal.
- **Cinza Superfície** (#F8FAFC): Fundo de cards, painéis, formulários. Separa visualmente sem precisar de borda ou sombra.
- **Cinza Borda** (#E2E8F0): Linhas divisórias sutis. Suficiente para estruturar sem distrair.
- **Cinza Lousa** (#1E293B): Texto principal. Contraste WCAG AA garantido contra fundos claros.
- **Cinza Legenda** (#64748B): Texto secundário, metadados, labels. Hierarquia visual sem perder legibilidade.

### Feedback

- **Vermelho Alerta** (#EF4444): Erros, validações, botões destrutivos.
- **Verde Confirmação** (#22C55E): Sucesso, confirmações, status "publicado".
- **Amarelo Atenção** (#EAB308): Avisos, status "rascunho", elementos pendentes.

## 3. Typography

**Display & Body Font:** Inter (com fallback system-ui, -apple-system, sans-serif)

**Character:** Inter é uma fonte geométrica suíça com excelente legibilidade em telas. Ela carrega a precisão técnica que o IPIL como instituto politécnico deve transmitir, sem ser fria ou distante. O contraste entre pesos é deliberadamente forte 800 para títulos que guiam o olhar, 400 para corpo que se lê sem esforço.

**Named Rule — A Regra do Respiro.** Nenhum bloco de texto ultrapassa 75 caracteres por linha. O espaçamento entre linhas (1.6 para corpo) e entre parágrafos (1.5x o font-size) é tão importante quanto o tipo escolhido.

### Hierarchy

- **Display** (800, clamp(2rem, 5vw, 3.5rem), 1.1, letter-spacing: -0.02em): Título hero da página inicial. Apenas para a notícia em destaque no topo. Máximo de 8 palavras.
- **Headline** (700, clamp(1.5rem, 3vw, 2.25rem), 1.2, letter-spacing: -0.01em): Títulos de página e títulos de notícia individual. A hierarquia começa aqui abaixo do display.
- **Title** (600, clamp(1.125rem, 2vw, 1.5rem), 1.3): Títulos de cards de notícia e subtítulos de seção.
- **Body** (400, 1rem, 1.6): Corpo de texto. Parágrafos, resumos, conteúdo de notícia. Largura máxima de linha: 75 caracteres. Corpo longo nunca abre em largura total do viewport.
- **Label** (500, 0.8125rem, 1.4, letter-spacing: 0.02em): Metadados (autor, data, categoria), badges, timestamps, navegação secundária.

## 4. Elevation

**Named Rule — A Regra do Plano.** A hierarquia visual é conquistada por cor e espaçamento, não por sombra. O sistema é predominantemente plano.

A elevação sutil existe apenas para dois propósitos:
1. **Distingir superfícies interativas** como cards de notícia, que usam `box-shadow: 0 1px 3px rgba(0,0,0,0.08)` em repouso e `0 4px 12px rgba(0,0,0,0.12)` em hover.
2. **Fixar elementos persistentes** como a navbar (sticky) e modais, que usam `box-shadow: 0 2px 8px rgba(0,0,0,0.1)`.

Fora destes casos, profundidade é criada com cores de fundo: uma seção com fundo Branco Papel [#FFF7ED] contra o Branco Giz [#FFFFFF] geral já estabelece hierarquia sem precisar de sombra alguma.

### Shadow Vocabulary

- **Card repouso** (`box-shadow: 0 1px 3px rgba(0,0,0,0.08)`): Cards de notícia no grid, painéis admin.
- **Card hover** (`box-shadow: 0 4px 12px rgba(0,0,0,0.12)`): Card sob cursor. Acompanhado de translateY(-2px).
- **Navbar fixa** (`box-shadow: 0 2px 8px rgba(0,0,0,0.1)`): Navbar pública e topbar admin.
- **Modal/dropdown** (`box-shadow: 0 8px 24px rgba(0,0,0,0.15)`): Janelas modais, dropdown menus, tooltips.

## 5. Components

### Buttons

- **Shape:** Cantos médios arredondados (8px). Botões primários têm preenchimento sólido; ghost buttons têm texto cor primária sem fundo.
- **Primary:** Fundo Laranja Institucional [#F97316], texto branco [#FFFFFF], padding 12px 24px. Fonte Inter 500, tamanho 0.9375rem.
- **Hover / Focus:** Fundo escurece para Laranja Farol [#EA580C]. Foco visível com outline 2px solid Laranja Suave [#FED7AA] e offset 2px. Transição de 200ms ease-out para background e transform.
- **Ghost / Secondary:** Background transparente, texto Laranja Institucional. Hover: background Laranja Suave [#FED7AA] com opacidade 0.3. Usado para ações secundárias e links do tipo "Ler mais".
- **Danger:** Background Vermelho Alerta [#EF4444], texto branco. Hover escurece 10%. Usado exclusivamente para ações destrutivas (eliminar notícia, remover utilizador).

### Cards / Containers

- **Corner Style:** Cantos arredondados (12px) para cards de notícia no grid público. Cantos médios (8px) para containers de formulário e painéis admin.
- **Background:** Fundo Branco Giz [#FFFFFF] para cards primários. Fundo Cinza Superfície [#F8FAFC] para painéis secundários.
- **Shadow Strategy:** Card repouso usa sombra sutil (ver Elevação). Nunca use borda colorida na lateral.
- **Border:** Cards não têm borda. A separação visual vem de background contrastante + sombra.
- **Internal Padding:** 20px (equivalente ao spacing md/lg). Consistente em todos os cards.

### Inputs / Fields

- **Style:** Borda sólida de 1px Cinza Borda [#E2E8F0], fundo Branco Giz [#FFFFFF], cantos médios (8px), padding 12px.
- **Focus:** Borda muda para Laranja Institucional [#F97316] com ring externo de 3px Laranja Suave [#FED7AA]. Transição 200ms ease-out.
- **Error:** Borda Vermelho Alerta [#EF4444], ring externo de 3px tom mais claro do vermelho. Mensagem de erro abaixo do campo em Label (0.8125rem).
- **Disabled:** Background Cinza Superfície [#F8FAFC], texto Cinza Legenda [#64748B], opacidade 0.6.

### Navigation (Navbar Pública)

- **Style:** Fundo Branco Giz [#FFFFFF], borda inferior 3px Laranja Institucional [#F97316]. Sticky no topo com z-index 100.
- **Logo:** Altura 40px. Versão colorida (logo.png) na navbar clara. Versão branca (logo-white.png) em contextos escuros.
- **Items:** Texto Inter 500, 1rem, Cinza Lousa [#1E293B]. Padding horizontal 16px. Hover: cor primária [#F97316].
- **Active:** Cor primária [#F97316] com opacidade extra.
- **Mobile:** Hamburger (24x24px, 3 linhas de 2px cada, cor Cinza Lousa). Menu slide-in lateral com fundo Branco Giz.

### Badges / Tags

- **Style:** Fundo da cor da categoria com opacidade 0.15, texto na cor plena, cantos arredondados (4px), padding 4px 10px. Fonte Label (0.8125rem, 500).
- **Variants:** Badge de categoria usa a cor definida na tabela `categorias`. Badge de status (rascunho/pendente/publicado) usa as cores funcionais (amarelo/cinza/verde).

## 6. Do's and Don'ts

### Do:
- **Do** usar o laranja [#F97316] como guia visual links, badges, botões primários, borda inferior da navbar. Cada instância deve ter um propósito de navegação ou destaque.
- **Do** manter o contraste WCAG AA: texto Cinza Lousa [#1E293B] nunca abaixo de 4.5:1 contra fundo Branco Giz [#FFFFFF].
- **Do** usar espaçamento generoso entre seções (padding vertical de 48px mínimo entre blocos de conteúdo).
- **Do** usar a sombra de card repouso (0 1px 3px rgba(0,0,0,0.08)) como padrão e a de hover (0 4px 12px rgba(0,0,0,0.12) + translateY(-2px)) como resposta.
- **Do** truncar títulos de notícias em no máximo 2 linhas no grid de cards, com ellipsis.

### Don't:
- **Don't** usar borda lateral colorida (border-left ou border-right >1px) em cards, listas, ou alertas. Use fundo completo ou nada.
- **Don't** usar texto gradiente com `background-clip: text`. Cor sólida sempre.
- **Don't** usar glassmorphism ou blur effects decorativos.
- **Don't** usar o laranja como fundo de página inteira ou de cards ele é guia, não base.
- **Don't** criar grids de cards idênticos com ícone + heading + texto repetitivo. Cada card de notícia tem imagem, badge, titulo, resumo, e metadados.
- **Don't** abrir modal como primeira opção para ações administrativas exiba formulários inline ou em página dedicada.
- **Don't** usar preto puro [#000] ou branco puro [#FFF]. Toda cor neutra leva um toque sutil da paleta.

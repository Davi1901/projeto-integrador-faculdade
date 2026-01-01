// Arquivo: produtos.js

const produtos = [
    {
        id: 1,
        marca: "PUMA",
        nome: "Tênis LaFrancé Moment Unissex",
        preco: "R$ 750,99",
        imagem_principal: "/DU.Hype/src/assets/tenis/Tenis_LaFrance_moment_Unissex1.jpg",
        imagens_thumb: ["/DU.Hype/src/assets/tenis/Tenis_LaFrance_moment_Unissex1.jpg", "/DU.Hype/src/assets/tenis/Tenis_LaFrance_moment_Unissex2.jpg", "/DU.Hype/src/assets/tenis/Tenis_LaFrance_moment_Unissex3.jpg", "/DU.Hype/src/assets/tenis/Tenis_LaFrance_moment_Unissex4.jpg"],
        descricao: "Apresentamos o tênis de basquete LaFracé Moment, que traz o estilo 'Not From Here' do Melo para nossa nova silhueta fora da quadra. Inspirado nas linguagens do amor, o esquema de cores em dois tons representa as diferentes emoções e vibrações de Melo. Esse drop mostra o frescor de Melo, mostrandoque ele está sempre pronto, dentro ou fora da quadra!"
    },
    {
        id: 2,
        marca: "NIKE",
        nome: "Tênis Nike Court Vision Low",
        preco: "R$ 350,99",
        imagem_principal: "/DU.Hype/src/assets/tenis/TenisNike_CourtVIsion_low1.jpg",
        imagens_thumb: ["/DU.Hype/src/assets/tenis/TenisNike_CourtVIsion_low1.jpg", "/DU.Hype/src/assets/tenis/TenisNike_CourtVision_low2.jpg", "/DU.Hype/src/assets/tenis/TenisNike_CourtVision_Low3.jpg", "/DU.Hype/src/assets/tenis/TenisNike_CourtVision_Low4.jpg"],
        descricao: "Adora o look clássico do basquete dos anos 80, mas tem uma queda pela cultura de ritmo acelerado dos jogos atuais? Conheça o Court Vision Low. Ele mantém a alma do original com material sintético impecável e camadas costuradas, enquanto o colarinho macio o mantém elegante e confortável para o seu mundo."
    },
    {
        id: 3,
        marca: "ADIDAS",
        nome: "Tênis Adidas Initiation",
        preco: "R$ 599,99",
        imagem_principal: "/DU.Hype/src/assets/tenis/Tenis_adidas_Initiation_Azul_1.jpg",
        imagens_thumb: ["/DU.Hype/src/assets/tenis/Tenis_adidas_Initiation_Azul_1.jpg", "/DU.Hype/src/assets/tenis/Tenis_adidas_Initiation_Azul_2.jpg", "/DU.Hype/src/assets/tenis/Tenis_adidas_Initiation_Azul_3.jpg", "/DU.Hype/src/assets/tenis/Tenis_adidas_Initiation_Azul_4.jpg"],
        descricao: "O tênis adidas Initiation foi criado para ajudar você a encontrar sua fluidez na quadra. A entressola Dreamstrike+ proporciona maior conforto em cada passada e corte, enquanto o forro têxtil mantém seus pés confortáveis até o apito final. O solado de borracha oferece a tração que você precisa para fazer seus melhores movimentos."
    },
    {
        id: 4,
        marca: "ADIDAS",
        nome: "Tênis Campus 00s Beta",
        preco: "R$ 699,99",
        imagem_principal: "/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Beta_Marrom_1.jpg",
        imagens_thumb: ["/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Beta_Marrom_1.jpg", "/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Beta_Marrom_2.jpg", "/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Beta_Marrom_3.jpg", "/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Beta_Marrom_4.jpg"],
        descricao: "O tênis Campus 00s Beta acompanha você aonde for. O suede premium envolve o cabedal, acentuado por detalhes perfurados e língua em malha elástica para maior fluxo de ar. Inspirado nos arquivos adidas do fim dos anos 90c este tênis de lifestule tem uma vibe old-school que está de volta."
    },
    {
        id: 5,
        marca: "ADIDAS",
        nome: "Tênis Tekkira Cup",
        preco: "R$ 597,90",
        imagem_principal: "/DU.Hype/src/assets/tenis/Tekkira_Cup_Branco_1.jpg",
        imagens_thumb: ["/DU.Hype/src/assets/tenis/Tekkira_Cup_Branco_1.jpg", "/DU.Hype/src/assets/tenis/Tekkira_Cup_Branco_2.jpg", "/DU.Hype/src/assets/tenis/Tekkira_Cup_Branco_3.jpg", "/DU.Hype/src/assets/tenis/Tekkira_Cup_Branco_4.jpg"],
        descricao: "Uma nova silhueta para skate que combina a estética de corrida do final dos anos 90 com o estilo inspirado nas quadras do início dos anos, o Tekkira Cup combina o conforto do cupsole com o desempenho."
    },
    {
        id: 6,
        marca: "ADIDAS",
        nome: "Tênis Campus 00s Preto",
        preco: "R$ 650,90",
        imagem_principal : "/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Preto_1.jpg",
        imagens_thumb : ["/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Preto_1.jpg", "/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Preto_2.jpg", "/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Preto_3.jpg", "/DU.Hype/src/assets/tenis/Tenis_Campus_00s_Preto_4.jpg"],
        descricao: "O tênis Campus pode ter nascido para as quadras, mas seu maior impacto já foi sentido longe delas. Este par não é execeção. Detalhes icônicos, como as Três Listras serrilhadas e o cupsole de borracha, são complementados por um cabedal têxtil de malha complexa."
    },
    {
        id: 7,
        marca: "NIKE",
        nome: "Air Max 90",
        preco: "R$ 490,99",
        imagem_principal : "/DU.Hype/src/assets/tenis/AirMax90_1.jpg",
        imagens_thumb : ["/DU.Hype/src/assets/tenis/AirMax90_1.jpg", "/DU.Hype/src/assets/tenis/AirMax90_2.jpg", "/DU.Hype/src/assets/tenis/AixMax90_3.jpg", "/DU.Hype/src/assets/tenis/AixMax90_4.jpg"],
        descricao: "Nada tão legal, nada tão confortável, nada tão comprovado. O Nike Air Max 90 permanece fiel às suas raízes de corrida do OG, com a emblemática sola Waffle, sobreposições costuradas e detalhes clássicos em TPU."
    },
    {
        id: 8,
        marca: "NIKE",
        nome: "Nike Dunk Low",
        preco: "R$ 799,99",
        imagem_principal: "/DU.Hype/src/assets/tenis/DunkLow_1.jpg", 
        imagens_thumb: ["/DU.Hype/src/assets/tenis/DunkLow_1.jpg", "/DU.Hype/src/assets/tenis/DunkLow_2.jpg", "/DU.Hype/src/assets/tenis/DunkLow_3.jpg", "/DU.Hype/src/assets/tenis/DunkLow_4.jpg"],
        descricao: "Você sempre pode contar com um clássico. O Dunk Low Retro combina um visual monocromático com materiais premium e estofamento de pelúcia para um conforto revolucionário que dura."
    },
    {
        id: 9,
        marca: "NIKE",
        nome: "Tênis Nike Air Force 1",
        preco: "R$ 699,99",
        imagem_principal: "/DU.Hype/src/assets/tenis/Tenis_airForce_1.jpg",
        imagens_thumb: ["/DU.Hype/src/assets/tenis/Tenis_airForce_1.jpg", "/DU.Hype/src/assets/tenis/Tenis_airForce_2.jpg", "/DU.Hype/src/assets/tenis/Tenis_airForce_3.jpg", "/DU.Hype/src/assets/tenis/Tenis_airForce_4.jpg"],
        descricao: "Confortável, durável e atemporal: não é à toa que ele é o número 1. A construção clássica dos anos 80 combina com detalhes arrojados para um estilo que acompanha você na quadra ou em qualquer lugar"
    }
];
<?php
/** 
|----------------------------------------------------------------------|
|               zOOm Media Gallery by José Costa                       |
|----------------------------------------------------------------------|
|                                                                      |
| Date: Julho, 2005                                                    |
| Author: José Manuel Costa, <http://www.jose.costa.net>               |
| Copyright:  José Costa                                               |
| Description: Uma excelente e fácil galeria de fotos para mambo.      |
|              zOOm Media Gallery tem excelentes características       |
|              estrutura hierárquica de directórios e subdirectorias   |
|              ilimitados para suas galerias, cria thumbnail           |
|              automaticoa, tens sistemas de zoom para utilizadores,   |
|              entre muitas outras características.                    |
| License: GPL                                                         |
| Filename: portuguese.php                                             |
| Version: 2.5                                                         |
| Link Project: http://zoom.ummagumma.nl/                              |
| Link Mambo Forge: http://mamboforge.net/projects/zoom/               |
|----------------------------------------------------------------------|
**/

// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//Language translation
define("_ZOOM_DATEFORMAT","%d.%m.%Y %H:%M"); // use the PHP strftime Format, more info at http://www.php.net
define("_ZOOM_ISO","iso-8859-1");
define("_ZOOM_PICK","Escolher a Galeria");
define("_ZOOM_DELETE","Apagar");
define("_ZOOM_BACK","Início");
define("_ZOOM_MAINSCREEN","Principal");
define("_ZOOM_BACKTOGALLERY","Voltar à galeria");
define("_ZOOM_INFO_DONE","ok!");
define("_ZOOM_TOOLTIP", "zOOm ToolTip");
define("_ZOOM_WARNING", "Alerta Galeria!");

//Gallery admin page
if ($zoom->_isAdmin || $zoom->_isUser){
define("_ZOOM_ADMINSYSTEM","Administração");
define("_ZOOM_USERSYSTEM","Sistema do Utilizador");
define("_ZOOM_ADMIN_TITLE","Sistema do Admin da Galeria de Imagens");
define("_ZOOM_USER_TITLE","Sistema do Utilizador da Galeria de Imagens");
define("_ZOOM_CATSMGR","Administração das Galerias");
define("_ZOOM_CATSMGR_DESCR","Criar,apagar novas galerias de imagens.");
define("_ZOOM_NEW","Nova Galeria -");
define("_ZOOM_DEL","Apagar galeria -");
define("_ZOOM_MEDIAMGR","Administrador de Imagens");
define("_ZOOM_MEDIAMGR_DESCR","Editar, apagar, procurar por imagens manualmente.");
define("_ZOOM_UPLOAD","Upload Arquivo(s)");
define("_ZOOM_EDIT","Editar Galeria");
define("_ZOOM_ADMIN_CREATE","Criar Base de Dados");
define("_ZOOM_ADMIN_CREATE_DESCR","construir as tabelas necessárias na base de dados para que possa começar a usar o álbum");
define("_ZOOM_HD_PREVIEW","Pré visualizar");
define("_ZOOM_HD_CHECKALL","Seleccionar/ Des-Seleccionar Todos");
define("_ZOOM_HD_CREATEDBY","Criado por");
define("_ZOOM_HD_AFTER","Inserir depois");
define("_ZOOM_HD_HIDEMSG","Esconder 'sem fotos' texto");
define("_ZOOM_HD_NAME","Dar novo nome a Galeria");
define("_ZOOM_HD_DIR","Directório");
define("_ZOOM_HD_NEW","Nova Galeria");
define("_ZOOM_HD_SHARE","Partilhar esta Galeria");
define("_ZOOM_TOPLEVEL","Topo do Nível");
define("_ZOOM_HD_UPLOAD","Upload Arquivo");
define("_ZOOM_A_ERROR_ERRORTYPE","Erro tipo");
define("_ZOOM_A_ERROR_IMAGENAME","Nome da Imagem");
define("_ZOOM_A_ERROR_NOFFMPEG","<u>FFmpeg</u> não detectado");
define("_ZOOM_A_ERROR_NOPDFTOTEXT","<u>PDFtoText</u> não detectado");
define("_ZOOM_A_ERROR_NOTINSTALLED","Não Instalado");
define("_ZOOM_A_ERROR_CONFTODB","Erro ao guardar configurações para base de dados!");
define("_ZOOM_A_MESS_NOT_SHURE","* Se não tem certeza, use o padrão \"auto\" ");
define("_ZOOM_A_MESS_SAFEMODE1","Note: \"Modo Seguro\" está activado, consequentemente é possível que uploads de imagens muito grande não funcione!<br />deve ir para o Sistema Administrativo e mudar para FTP-Mode.");
define("_ZOOM_A_MESS_SAFEMODE2","Note: \"Modo Seguro\" está activado, consequentemente é possível que uploads de arquivos muito grande não funcione!<br />zOOm recomenda activar o FTP-Mode no Sistema Administrativo.");
define("_ZOOM_A_MESS_PROCESSING_FILE","Processando arquivo...");
define("_ZOOM_A_MESS_NOTOPEN_URL","Não pode abrir o URL:");
define("_ZOOM_A_MESS_PARSE_URL","Analisando \"%s\" para imagens... "); // %s = $url
define("_ZOOM_A_MESS_NOJAVA","Se vê acima apenas uma caixa cinza ou esta tendo problemas enquanto descarregar novas imagens, deve ser por que <br />não tem o último java run-time instalado. Visite <a href=\"http://www.java.com\" target=\"_blank\">Java.com</a> <br /> e descarregar as versões mais actuais.");
define("_ZOOM_SETTINGS","Configurações");
define("_ZOOM_SETTINGS_DESCR","Visualizar e alterar todas as configurações do Componente zoom.");
define("_ZOOM_SETTINGS_TAB1","Sistema");
define("_ZOOM_SETTINGS_TAB2","Layout");
define("_ZOOM_SETTINGS_TAB4","Acesso");
define("_ZOOM_SETTINGS_TAB5","Modo Seguro");
define("_ZOOM_SETTINGS_CONVTYPE","Tipo de conversão");
define("_ZOOM_SETTINGS_AUTODET","auto-detectado: ");
define("_ZOOM_SETTINGS_IMGPATH","Endereço para os arquivos de imagens:");
define("_ZOOM_SETTINGS_TTIMGPATH","O endereço actual para as fotos é ");
define("_ZOOM_SETTINGS_CONVSETTINGS","Configuração da conversão de imagens:");
define("_ZOOM_SETTINGS_IMPATH","Endereço para o ImageMagick: ");
define("_ZOOM_SETTINGS_NETPBMPATH"," ou NetPBM: ");
define("_ZOOM_SETTINGS_FFMPEGPATH","Endereço para FFmpeg");
define("_ZOOM_SETTINGS_FFMPEGTOOLTIP","FFmpeg é necessário para criar thumbnail dos seus arquivos de vídeo.<br />As extensões suportadas são: ");
define("_ZOOM_SETTINGS_PDFTOTEXTPATH","Endereço para PDFtoText");
define("_ZOOM_SETTINGS_XPDFTOOLTIP","pdf2text, que é parte do pacote Xpdf, é necessário para a indexação dos arquivos PDF.");
define("_ZOOM_SETTINGS_MAXSIZE","Tamanho max. da Imagem: ");
define("_ZOOM_SETTINGS_THUMBSETTINGS","Configuração do Thumbnail:");
define("_ZOOM_SETTINGS_QUALITY","Qualidade NetPBM e GD2 JPEG: ");
define("_ZOOM_SETTINGS_SIZE","Tamanho max. do Thumbnail: ");
define("_ZOOM_SETTINGS_TEMPNAME","Nome Temporário: ");
define("_ZOOM_SETTINGS_AUTONUMBER","Auto Numerar Nome das Imagens ex:1,2,3");
define("_ZOOM_SETTINGS_TEMPDESCR","Descrição Temporária: ");
define("_ZOOM_SETTINGS_TITLE","Título da Galeria:");
define("_ZOOM_SETTINGS_SUBCATSPG","Núm. de colunas das (sub)galerias");
define("_ZOOM_SETTINGS_COLUMNS","Núm. de Colunas do Thumbnail");
define("_ZOOM_SETTINGS_THUMBSPG","Thumbs por página");
define("_ZOOM_SETTINGS_CMTLENGTH","Tamanho max. Para Comentário");
define("_ZOOM_SETTINGS_CHARS","caracteres");
define("_ZOOM_SETTINGS_GALLERYPREFIX","Prefixo do Título da Galeria");
define("_ZOOM_SETTINGS_COMMENTS","Comentários");
define("_ZOOM_SETTINGS_POPUP","PopUp Imagem");
define("_ZOOM_SETTINGS_CATIMG","Mostrar Imagem da Categoria");
define("_ZOOM_SETTINGS_SLIDESHOW","Slideshow");
define("_ZOOM_SETTINGS_ZOOMLOGO","Mostrar Logo zOOm");
define("_ZOOM_SETTINGS_SHOWHITS","Mostrar o no. de hits");
define("_ZOOM_SETTINGS_READEXIF","Ler EXIF-data");
define("_ZOOM_SETTINGS_EXIFTOOLTIP","Esta característica vai exibir dados adicionais de  EXIF e PTC, sem a necessidade do módulo EXIF para o PHP ser instalado no seu sistema.");
define("_ZOOM_SETTINGS_READID3","Ler mp3 ID3-data");
define("_ZOOM_SETTINGS_ID3TOOLTIP","Esta característica vai exibir dados adicionais de ID3 v1.1 e v2.0 quando estiver visualizando os detalhes de um arquivo mp3.");
define("_ZOOM_SETTINGS_RATING","Classificação");
define("_ZOOM_SETTINGS_CSS","Janela Pop-up css");
define("_ZOOM_SETTINGS_USERUPL","Deixar os utilizadores fazerem upload:");
define("_ZOOM_SETTINGS_ACCESSLVL","Nível de acesso: ");
define("_ZOOM_SETTINGS_SUCCESS","Configuração actualizada com sucesso!");
define("_ZOOM_SETTINGS_ZOOMING","Imagem zOOm");
define("_ZOOM_SETTINGS_ORDERBY","Ordenar o Thumbnail por");
define("_ZOOM_SETTINGS_CATORDERBY","método de ordenar (sub)Galerias; ordenado por");
define("_ZOOM_SETTINGS_DATE_ASC","DATA, crescente");
define("_ZOOM_SETTINGS_DATE_DESC","DATA, decrescente");
define("_ZOOM_SETTINGS_FLNM_ASC","NOME DO FICHEIRO, crescente");
define("_ZOOM_SETTINGS_FLNM_DESC","NOME DO FICHEIRO, decrescente");
define("_ZOOM_SETTINGS_NAME_ASC","NOME, crescente");
define("_ZOOM_SETTINGS_NAME_DESC","NOME, decrescente");
define("_ZOOM_SETTINGS_LBTOOLTIP","Um lightbox é como um carrinho de compras preenchido com as imagens que seleccionou, que pode ser descarregado como um arquivo ZIP.");
define("_ZOOM_SETTINGS_SHOWNAME","Mostrar Nome");
define("_ZOOM_SETTINGS_SHOWDESCR","Mostrar Descrição");
define("_ZOOM_SETTINGS_SHOWKEYWORDS","Mostrar Palavras-chave");
define("_ZOOM_SETTINGS_SHOWDATE","Mostrar data");
define("_ZOOM_SETTINGS_SHOWFILENAME","Mostrar o Nome do Arquivo");
define("_ZOOM_SETTINGS_METABOX","Mostrar Caixa Flutuante Com os Detalhes nas Páginas das Galerias");
define("_ZOOM_SETTINGS_METABOXTOOLTIP","Desselecione esta característica para melhorar a velocidade de sua galeria. Eficiente com base de dados grandes.");
define("_ZOOM_SETTINGS_ECARDS","Cartões");
define("_ZOOM_SETTINGS_ECARDS_LIFETIME","Duração dos Cartões");
define("_ZOOM_SETTINGS_ECARDS_ONEWEEK","uma semana");
define("_ZOOM_SETTINGS_ECARDS_TWOWEEKS","duas semanas");
define("_ZOOM_SETTINGS_ECARDS_ONEMONTH","um mês");
define("_ZOOM_SETTINGS_ECARDS_THREEMONTHS","três meses");
define("_ZOOM_SETTINGS_SHOWSEARCH","Campo de Procura em Todas as Páginas");
define("_ZOOM_SETTINGS_ALLOWCREATE","Permitir que os utilizadores criem Galerias");
define("_ZOOM_SETTINGS_ALLOWDEL","Permitir que utilizadores eliminem galerias partilhadas");
define("_ZOOM_SETTINGS_ALLOWEDIT","Permitir que utilizadores editem Galerias partilhadas");
define("_ZOOM_SETTINGS_SETMENUOPTION","Mostrar o link 'Descarregar Imagens'no menu do utilizadores");
define("_ZOOM_SETTINGS_USEFTP","Usar Modo FTP?");
define("_ZOOM_SETTINGS_FTPHOST","Nome do Host");
define("_ZOOM_SETTINGS_FTPUNAME","Nome de Utilizadores");
define("_ZOOM_SETTINGS_FTPPASS","Palavra-Passe");
define("_ZOOM_SETTINGS_FTPWARNING","Atenção: A Palavra-Passe não é guardada com segurança!");
define("_ZOOM_SETTINGS_FTPHOSTDIR","Directório no Host");
define("_ZOOM_SETTINGS_MESS_FTPHOSTDIR","Por favor forneça o directório para o Mambo de seu ftp-root aqui. IMPORTANTANTE: Terminar <b>sem</b> um slash ou um backslash!");
define("_ZOOM_YES","sim");
define("_ZOOM_NO","não");
define("_ZOOM_SAVE","Guardar");
define("_ZOOM_MOVEFILES","Mover Imagem");
define("_ZOOM_MOVEFILES_DESCR","Mover fotos entre galerias");
define("_ZOOM_BUTTON_MOVE","Mover");
define("_ZOOM_MOVEFILES_STEP1","Passo 1: Seleccione a Galeria Fonte");
define("_ZOOM_MOVEFILES_STEP2","Passo 2: Seleccione as Imagens que Deseja Mover");
define("_ZOOM_MOVEFILES_STEP3","Passo 3: Seleccione a Galeria de destino & mova as imagens");
define("_ZOOM_ALERT_MOVEOK","Imagens movidas com sucesso!");
define("_ZOOM_OPTIMIZE","Optimizar tabelas");
define("_ZOOM_OPTIMIZE_DESCR","A Galeria de Imagens zOOm usa muito tabelas e cria assim muitos dados sem importância. Clique aqui para remover o lixo.");
define("_ZOOM_OPTIMIZE_SUCCESS","A Galeria de Imagens zOOm Media foi optimizada!");
define("_ZOOM_UPDATE","Actualizar Galeria de Imagens zOOm");
define("_ZOOM_UPDATE_DESCR","Adicionar novas características, solucionar problemas e resolver bugs! Verfique <a href=\"http://zoom.ummagumma.nl\" target=\"_blank\">zoom.ummagumma.nl</a> para as últimas actualizações!");
define("_ZOOM_UPDATE_XMLDATE","Data da última actualização");
define("_ZOOM_UPDATE_PACKAGE","Arquivo ZIP de actualização: ");
define("_ZOOM_CREDITS","Créditos do Componente Zoom Media Gallery");

//Image actions
define("_ZOOM_UPLOAD_SINGLE","Ficheiro ZIP");
define("_ZOOM_UPLOAD_MULTIPLE","multiplos ficheiros");
define("_ZOOM_UPLOAD_DRAGNDROP","Arrastar");
define("_ZOOM_UPLOAD_SCANDIR","procurar directório");
define("_ZOOM_UPLOAD_INTRO","Clique no <b>Browse</b> para localizar a imagem para upload.");
define("_ZOOM_UPLOAD_STEP1","1. Selecione o número de ficheiros que quer fazer upload: ");
define("_ZOOM_UPLOAD_STEP2","2. Selecione a galeria onde quer fazer upload dos ficheiros: ");
define("_ZOOM_UPLOAD_STEP3","3. Use o botão Browse para procurar as imagens no seu computador");
define("_ZOOM_SCAN_STEP1","Step 1: forneça um local para que as fotos sejam procuradas...");
define("_ZOOM_SCAN_STEP2","Step 2: seleccione os arquivos que deseija copiar...");
define("_ZOOM_SCAN_STEP3","Step 3: A Galeria processou os arquivos que seleccionou...");
define("_ZOOM_SCAN_STEP1_DESCR","O local pode ser tanto um URL como um directório no Servidor.<br />&nbsp;   Tip: FTP as imagens para um directório no seu servidor e então forneça o caminho do directório aqui!");
define("_ZOOM_SCAN_STEP2_DESCR1","Processando");
define("_ZOOM_SCAN_STEP2_DESCR2","como um diretório local");
define("_ZOOM_FORMCREATE_NAME","Nome");
define("_ZOOM_FORM_IMAGEFILE","Imagem");
define("_ZOOM_FORM_IMAGEFILTER","Tipos de Imagens Suportadas");
define("_ZOOM_FORM_INGALLERY","Na galeria");
define("_ZOOM_FORM_SETFILENAME","Definir o nome da imagem igual ao nome original.");
define("_ZOOM_FORM_LOCATION","Localização");
define("_ZOOM_BUTTON_SCAN","Enviar URL ou diretório");
define("_ZOOM_BUTTON_UPLOAD","Upload");
define("_ZOOM_BUTTON_EDIT","Editar");
define("_ZOOM_BUTTON_CREATE","Criar");
define("_ZOOM_CONFIRM_DEL","Esta opção vai remover uma Galeria na totalidade, incluindo as imagens!\\nDeseja mesmo prosseguir?");
define("_ZOOM_CONFIRM_DELMEDIUM","Quer remover completamente este media!\\ nDeseja mesmo prosseguir?");
define("_ZOOM_ALERT_DEL","Galeria foi apagada!");
define("_ZOOM_ALERT_NOCAT","Nenhuma Galeria foi seleccionada!");
define("_ZOOM_ALERT_NOMEDIA","Nenhuma imagem seleccionada!");
define("_ZOOM_ALERT_EDITOK","Espaços da Galeria foram editados com sucesso!");
define("_ZOOM_ALERT_NEWGALLERY","Nova Galeria criada.");
define("_ZOOM_ALERT_NONEWGALLERY","Galeria não criada!!.");
define("_ZOOM_ALERT_EDITIMG","Propriedades das imagens editadas com sucesso");
define("_ZOOM_ALERT_DELPIC","Imagem apagada!");
define("_ZOOM_ALERT_NODELPIC","Imagem não pode ser apagada!");
define("_ZOOM_ALERT_NOPICSELECTED","Nenhuma imagem selecionada.");
define("_ZOOM_ALERT_NOPICSELECTED_MULT","Nenhuma imagem selecionada.");
define("_ZOOM_ALERT_UPLOADOK","Upload de nova Imagem com sucesso!");
define("_ZOOM_ALERT_UPLOADSOK","Upload de nova imagem com sucesso!");
define("_ZOOM_ALERT_WRONGFORMAT","Formato de imagem inválido.");
define("_ZOOM_ALERT_WRONGFORMAT_MULT","Formato de imagem inválido.");
define("_ZOOM_ALERT_MOVEFAILURE","Erro ao mover ficheiro.");
define("_ZOOM_ALERT_IMGERROR","Erro a ajustar tamanho da imagem/ criando thumbnail.");
define("_ZOOM_ALERT_PCLZIPERROR","Erro na extracção arquivo.");
define("_ZOOM_ALERT_INDEXERROR","Erro na indexação no documento.");
define("_ZOOM_ALERT_IMGFOUND","imagem(s) encontradas.");
define("_ZOOM_INFO_CHECKCAT","Por Favor selecione uma galeria antes de carregar no botão upload!");
define("_ZOOM_BUTTON_ADDIMAGES","Adicionar imagem");
define("_ZOOM_BUTTON_REMIMAGES","Remover imagem");
define("_ZOOM_INFO_PROCESSING","Processar arquivo:");
define("_ZOOM_ITEMEDIT_TAB1","Propriedades");
define("_ZOOM_ITEMEDIT_TAB2","Membros");
define("_ZOOM_USERSLIST_LINE1",">>Seleccione os membros deste item<<");
define("_ZOOM_USERSLIST_ALLOWALL",">>Acesso Público<<");
define("_ZOOM_USERSLIST_MEMBERSONLY",">>Somente Membros<<");
define("_ZOOM_PUBLISHED","Publicar");
define("_ZOOM_SHARED","Partilhada");
define("_ZOOM_ROTATE","Rodar a imagem em 90º");
define("_ZOOM_CLOCKWISE","clockwise");
define("_ZOOM_CCLOCKWISE","counter clockwise");
}

//Navigation (including Slideshow buttons)
define("_ZOOM_SLIDESHOW","Slideshow:");
define("_ZOOM_PREV_IMG","imagem anterior");
define("_ZOOM_NEXT_IMG","imagem seguinte");
define("_ZOOM_FIRST_IMG","primeira imagem");
define("_ZOOM_LAST_IMG","última imagem");
define("_ZOOM_PLAY","iniciar");
define("_ZOOM_STOP","parar");
define("_ZOOM_RESET","reset");
define("_ZOOM_FIRST","Primeiro");
define("_ZOOM_LAST","Último");
define("_ZOOM_PREVIOUS","Anterior");
define("_ZOOM_NEXT","Seguinte");

//Gallery actions
define("_ZOOM_SEARCH_BOX","Procura rápida...");
define("_ZOOM_ADVANCED_SEARCH","Pesquisa Avançada");
define("_ZOOM_SEARCH_KEYWORD","Pesquisar Palavra");
define("_ZOOM_IMAGES","imagens");
define("_ZOOM_IMGFOUND","encontrado - você está na página");
define("_ZOOM_IMGFOUND2","de");
define("_ZOOM_SUBGALLERIES","sub-galerias");
define("_ZOOM_ALERT_COMMENTOK","O seu comentário foi adicionado com sucesso!");
define("_ZOOM_ALERT_COMMENTERROR","Você já comentou esta imagem!");
define("_ZOOM_ALERT_VOTE_OK","O seu voto foi aprovado! Obrigado.");
define("_ZOOM_ALERT_VOTE_ERROR","Você já votou para esta imagem!");
define("_ZOOM_WINDOW_CLOSE","Fechar");
define("_ZOOM_NOPICS","Nenhuma imagem na galeria");
define("_ZOOM_PROPERTIES","Propriedades");
define("_ZOOM_COMMENTS","Comentários");
define("_ZOOM_NO_COMMENTS","Nenhum comentário adicionado ainda.");
define("_ZOOM_YOUR_NAME","Nome");
define("_ZOOM_ADD","Adicionar");
define("_ZOOM_NAME","Nome");
define("_ZOOM_DATE","Data");
define("_ZOOM_DESCRIPTION","Descrição");
define("_ZOOM_IMGNAME","Nome");
define("_ZOOM_FILENAME","Nome do Ficheiro");
define("_ZOOM_CLICKDOCUMENT","(clique no nome do arquivo para abrir o documento)");
define("_ZOOM_KEYWORDS","Palavras-chave");
define("_ZOOM_HITS","cliques");
define("_ZOOM_CLOSE","Fechar");
define("_ZOOM_NOIMG", "Nenhuma imagem encontrada!");
define("_ZOOM_NONAME", "Introduza um nome!");
define("_ZOOM_NOCAT", "Nenhuma galeria selecionada!");
define("_ZOOM_EDITPIC", "Editar Imagem");
define("_ZOOM_SETCATIMG","Definir como galeria de imagens");
define("_ZOOM_SETPARENTIMG","Definir como Imagem da Galeria de uma Galeria PRINCIPAL");
define("_ZOOM_PASS","Palavra-Passe");
define("_ZOOM_PASS_REQUIRED","Esta Galeria precisa de uma palavra-passe.<br />Por favor, preencha o campo da palavra-passe<br />e pressione o botão OK. Obrigado.");
define("_ZOOM_PASS_BUTTON","OK");
define("_ZOOM_PASS_GALLERY","palavra-passe");
define("_ZOOM_PASS_INNCORRECT","Palavra-passe Incorrecta");

//Lightbox
define("_ZOOM_LIGHTBOX","Lightbox");
define("_ZOOM_LIGHTBOX_GALLERY","Lightbox esta galeria!");
define("_ZOOM_LIGHTBOX_ITEM","Lightbox este item!");
define("_ZOOM_LIGHTBOX_VIEW","Visualizar seu Lightbox");
define("_ZOOM_YOUR_LIGHTBOX","O Conteúdo do seu Lightbox:");
define("_ZOOM_LIGHTBOX_ZIPBTN","Criar arquivo ZIP");
define("_ZOOM_LIGHTBOX_CATS","Galerias");
define("_ZOOM_LIGHTBOX_TITLEDESCR","Título & Descrição");
define("_ZOOM_ACTION","Acção");
define("_ZOOM_LIGHTBOX_ADDED","Item adicionado ao seu lightbox com sucesso!");
define("_ZOOM_LIGHTBOX_NOTADDED","Erro a adicionar item ao seu lightbox!");
define("_ZOOM_LIGHTBOX_EDITED","Item editado com sucesso!");
define("_ZOOM_LIGHTBOX_NOTEDITED","Erro na edição do item!");
define("_ZOOM_LIGHTBOX_DEL","Item removido do seu lightbox com sucesso!");
define("_ZOOM_LIGHTBOX_NOTDEL","Erro a remover item do seu lightbox!");
define("_ZOOM_LIGHTBOX_NOZIP","Você já criou um arquivo Zip do seu lightbox ou o seu lightbox não contém nenhum item!");
define("_ZOOM_LIGHTBOX_PARSEZIP","Analisando imagens da Galeria...");
define("_ZOOM_LIGHTBOX_DOZIP","criando arquivo ZIP...");
define("_ZOOM_LIGHTBOX_DLHERE","Agora pode fazer download do seu lightbox");

//EXIF information
define("_ZOOM_EXIF","EXIF");
define("_ZOOM_EXIF_SHOWHIDE","Mostrar/ ocultar Metadata");

//MP3 id3 v1.1 or later information
define("_ZOOM_AUDIO_PLAYING","em reprodução:");
define("_ZOOM_AUDIO_CLICKTOPLAY","Clique aqui para mostrar arquivo.");
define("_ZOOM_ID3","ID3");
define("_ZOOM_ID3_SHOWHIDE","Mostrar/ ocultar dados ID3-tag");
define("_ZOOM_ID3_LENGTH","Taxa");
define("_ZOOM_ID3_TITLE","Título");
define("_ZOOM_ID3_ARTIST","Artista");
define("_ZOOM_ID3_ALBUM","Álbum");
define("_ZOOM_ID3_YEAR","Ano");
define("_ZOOM_ID3_COMMENT","Comentário");
define("_ZOOM_ID3_GENRE","Género");

//rating
define("_ZOOM_RATING","Classificação");
define("_ZOOM_NOTRATED","Não classificado ainda!");
define("_ZOOM_VOTE","votar");
define("_ZOOM_VOTES","votos");
define("_ZOOM_RATE0","lixo");
define("_ZOOM_RATE1","fraco");
define("_ZOOM_RATE2","medio");
define("_ZOOM_RATE3","bom");
define("_ZOOM_RATE4","muito bom");
define("_ZOOM_RATE5","perfeito!");

//special
define("_ZOOM_TOPTEN","Top 10");
define("_ZOOM_LASTSUBM","Última submissão");
define("_ZOOM_LASTCOMM","Último comentário");
define("_ZOOM_SEARCHRESULTS","Procurar resultados");
define("_ZOOM_TOPRATED","Top Classificado");

//ecard
define("_ZOOM_ECARD_SENDAS","Enviar esta foto como um Cartão para um amigo!");
define("_ZOOM_ECARD_YOURNAME","O seu nome");
define("_ZOOM_ECARD_YOUREMAIL","O seu endereço de e-mail");
define("_ZOOM_ECARD_FRIENDSNAME","O nome do seu amigo/a");
define("_ZOOM_ECARD_FRIENDSEMAIL","O e-mail do seu amigo/a");
define("_ZOOM_ECARD_MESSAGE","Mensagem");
define("_ZOOM_ECARD_SENDCARD","Enviar Cartão");
define("_ZOOM_ECARD_SUCCESS","Cartão enviado com sucesso.");
define("_ZOOM_ECARD_CLICKHERE","Clique aqui para visualiza-lo!");
define("_ZOOM_ECARD_ERROR","Erro a enviar o Cartão para");
define("_ZOOM_ECARD_TURN","Olhe atrás deste Cartão!");
define("_ZOOM_ECARD_TURN2","Olhe a frente deste Cartão!");
define("_ZOOM_ECARD_SENDER","Enviado por:");
define("_ZOOM_ECARD_SUBJ","Recebeu um Cartão de:");
define("_ZOOM_ECARD_MSG1","enviado para você um Cartão de");
define("_ZOOM_ECARD_MSG2","Clique no link abaixo para ver o seu Cartão pessoal!");
define("_ZOOM_ECARD_MSG3","Não responda a este email, pois ele foi gerado automaticamente.");

//installation-screen
define ('_ZOOM_INSTALL_CREATE_DIR','A instalação do zOOm está a tentar criar o directório de imagens "images/zoom" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_SUCC','pronto!');
define ('_ZOOM_INSTALL_CREATE_DIR_FAIL','falhou!');
define ('_ZOOM_INSTALL_MESS1','A Galeria de imagens zOOm foi instalada com sucesso.<br>Agora já pode criar seus álbuns!');
define ('_ZOOM_INSTALL_MESS2','NOTA: a primeira coisa que deve fazer agora, é ir para o menu dos componentes acima,<br>procurar pelo título "Administração zOOm Media Gallery", click no link<br>ver o painel administrativo.');
define ('_ZOOM_INSTALL_MESS3','Aqui pode ajustar todas as variáveis do zOOm para a sua configuração.');
define ('_ZOOM_INSTALL_MESS4','Não se esqueça de criar uma Galeria, a partir daí, é sua responsabilidade!');
define ('_ZOOM_INSTALL_MESS_FAIL1','A Galeria zOOM não pode ser instalada com sucesso!');
define ('_ZOOM_INSTALL_MESS_FAIL2','Os seguintes directórios devem ser criados e mais tarde o seu chmod mudado para  "0777":<br />'
. '"images/zoom"<br />'
. '/components/com_zoom/images"<br />'
. '"/components/com_zoom/admin"<br />'
. '"/components/com_zoom/classes"<br />'
. '"/components/com_zoom/images"<br />'
. '"/components/com_zoom/images/admin"<br />'
. '"/components/com_zoom/images/filetypes"<br />'
. '"/components/com_zoom/images/rating"<br />'
. '"/components/com_zoom/images/smilies"<br />'
. '"/components/com_zoom/language"<br />'
. '"/components/com_zoom/tabs"');
define ('_ZOOM_INSTALL_MESS_FAIL3','Uma vez que tenha criado todos os directórios e mudados os direitos, vá para <br /> "Componentes -> zOOm Media Gallery" e escolha suas configurações.');

//Module Language
define("_ZOOM_M_config","Módulo");
define("_ZOOM_M_method","Visualizar Método");
define("_ZOOM_M_all","tudo");
define("_ZOOM_M_random","aleatório");
define("_ZOOM_M_newest","novo");
define("_ZOOM_M_hits","cliques");
define("_ZOOM_M_votes","votos");
define("_ZOOM_M_count","Número de Imagens:"); 
define("_ZOOM_M_lastup","Última Actualização:");
define("_ZOOM_M_admin_count","Mostrar o Número de Imagens:");
define("_ZOOM_M_admin_lastup","Mostrar a Última Actualização:");
define("_ZOOM_M_admin_cats","Mostrar catnames:");
define("_ZOOM_M_admin_meth","Mostrar Método:");
define("_ZOOM_M_admin_df","Formato da Data:");
?>

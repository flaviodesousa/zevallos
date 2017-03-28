USE [om_d2_net_br]
GO
/****** Object:  Table [dbo].[pubAutor]    Script Date: 09/05/2010 00:56:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubAutor](
	[autCodigo] [int] IDENTITY(1,1) NOT NULL,
	[autSigla] [varchar](20) NULL,
	[autNome] [varchar](50) NOT NULL,
	[autDescricao] [varchar](250) NULL,
	[autEMail] [varchar](100) NULL,
	[autURL] [varchar](255) NULL,
	[autInclusao] [datetime] NOT NULL CONSTRAINT [DF_pubAutor_autInclusao]  DEFAULT (getdate()),
	[autAlteracao] [datetime] NULL,
	[autAtivo] [tinyint] NOT NULL CONSTRAINT [DF_pubAutor_autAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_pubAutor] PRIMARY KEY CLUSTERED 
(
	[autCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pubEnqueteAssunto]    Script Date: 09/05/2010 00:59:33 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubEnqueteAssunto](
	[enaCodigo] [int] IDENTITY(1,1) NOT NULL,
	[enaNome] [varchar](100) NULL,
	[enaDescricao] [varchar](500) NULL,
	[enaInclusao] [datetime] NOT NULL,
	[enaAlteracao] [datetime] NULL,
	[enaAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubEnqueteAssunto] PRIMARY KEY CLUSTERED 
(
	[enaCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubEnqueteAssuntoNome] ON [dbo].[pubEnqueteAssunto] 
(
	[enaNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[livAutor]    Script Date: 09/05/2010 00:51:35 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[livAutor](
	[autCodigo] [int] IDENTITY(1,1) NOT NULL,
	[autSigla] [varchar](50) NULL,
	[autNome] [varchar](50) NOT NULL,
	[autDescricao] [varchar](1000) NULL,
	[autArtistId] [int] NULL,
	[autInclusao] [datetime] NOT NULL CONSTRAINT [DF_livAutor_autInclusao]  DEFAULT (getdate()),
	[autAlteracao] [datetime] NULL,
	[autAtivo] [tinyint] NOT NULL CONSTRAINT [DF_livAutor_autAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_livAutor] PRIMARY KEY CLUSTERED 
(
	[autCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_livAutorArtistId] ON [dbo].[livAutor] 
(
	[autArtistId] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_livAutorNome] ON [dbo].[livAutor] 
(
	[autNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_livAutorSigla] ON [dbo].[livAutor] 
(
	[autSigla] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubImagemCatalogo]    Script Date: 09/05/2010 01:02:58 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubImagemCatalogo](
	[imcCodigo] [int] IDENTITY(1,1) NOT NULL,
	[imcNome] [varchar](50) NOT NULL,
	[imcDescricao] [varchar](250) NULL,
	[imcLayout] [tinyint] NULL,
	[imcLinhas] [tinyint] NULL,
	[imcColunas] [tinyint] NULL,
	[imcPublico] [tinyint] NULL,
	[imcInclusao] [datetime] NOT NULL,
	[imcAlteracao] [datetime] NULL,
	[imcAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubImagemCatalogo] PRIMARY KEY CLUSTERED 
(
	[imcCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubImagemCatalogoNome] ON [dbo].[pubImagemCatalogo] 
(
	[imcNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubLayout]    Script Date: 09/05/2010 01:03:19 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubLayout](
	[layCodigo] [int] IDENTITY(1,1) NOT NULL,
	[layNome] [varchar](50) NOT NULL,
	[layArquivo] [varchar](50) NULL,
	[layInclusao] [datetime] NOT NULL,
	[layAlteracao] [datetime] NULL,
	[layAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubLayout] PRIMARY KEY CLUSTERED 
(
	[layCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubLayoutNome] ON [dbo].[pubLayout] 
(
	[layNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubOperadorTipo]    Script Date: 09/05/2010 01:04:49 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubOperadorTipo](
	[optCodigo] [int] IDENTITY(1,1) NOT NULL,
	[optSigla] [varchar](20) NULL,
	[optNome] [varchar](40) NULL,
	[optDescricao] [varchar](255) NULL,
	[optInclusao] [datetime] NOT NULL,
	[optAlteracao] [datetime] NULL,
	[optAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubOperadorTipo] PRIMARY KEY CLUSTERED 
(
	[optCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pubPalavra]    Script Date: 09/05/2010 01:10:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubPalavra](
	[palCodigo] [int] IDENTITY(1,1) NOT NULL,
	[palNome] [nvarchar](50) NOT NULL,
	[palNomeNormalized] [varchar](50) NOT NULL,
	[palNomeCorreto] [nvarchar](50) NOT NULL,
	[palPalavraCorreta] [int] NULL,
	[palPagina] [tinyint] NULL CONSTRAINT [DF_pubPalavra_palPagina]  DEFAULT ((0)),
	[palBuscador] [tinyint] NULL CONSTRAINT [DF_pubPalavra_palBuscador]  DEFAULT ((0)),
	[palSigla] [tinyint] NULL,
	[palSiglaTraducao] [varchar](255) NULL,
	[palPaginas] [int] NULL CONSTRAINT [DF_pubPalavra_palPaginas]  DEFAULT ((0)),
	[palQuantidade] [int] NULL CONSTRAINT [DF_pubPaginaPalavra_papQuantidade]  DEFAULT ((1)),
	[palGoogle] [int] NULL CONSTRAINT [DF_pubPalavra_palGoogle]  DEFAULT ((0)),
	[palLive] [int] NULL CONSTRAINT [DF_pubPalavra_palMSN]  DEFAULT ((0)),
	[palYahoo] [int] NULL CONSTRAINT [DF_pubPalavra_palYahoo]  DEFAULT ((0)),
	[palAltavista] [int] NULL CONSTRAINT [DF_pubPalavra_palAltavista]  DEFAULT ((0)),
	[palOutros] [int] NULL CONSTRAINT [DF_pubPalavra_palOutros]  DEFAULT ((0)),
	[palInclusao] [datetime] NOT NULL CONSTRAINT [DF_pubPaginaPalavra_papInclusao]  DEFAULT (getdate()),
	[palAlteracao] [datetime] NULL,
	[palAtivo] [tinyint] NOT NULL CONSTRAINT [DF_pubPalavra_palAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_pubPaginaPalavra] PRIMARY KEY CLUSTERED 
(
	[palCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraGoogle] ON [dbo].[pubPalavra] 
(
	[palGoogle] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraNome] ON [dbo].[pubPalavra] 
(
	[palNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraNomeCorreto] ON [dbo].[pubPalavra] 
(
	[palNomeNormalized] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraNomeNormalized] ON [dbo].[pubPalavra] 
(
	[palNomeNormalized] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraQuantidade] ON [dbo].[pubPalavra] 
(
	[palQuantidade] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubPaginas]    Script Date: 09/05/2010 01:08:05 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubPaginas](
	[pagCodigo] [int] IDENTITY(1,1) NOT NULL,
	[pagPai] [int] NULL,
	[pagJPOld] [int] NULL,
	[pagOrdem] [varchar](50) NULL,
	[pagNome] [varchar](255) NOT NULL,
	[pagTitulo] [varchar](255) NULL,
	[pagTituloFlutuante] [varchar](255) NULL,
	[pagResumo] [varchar](1000) NULL,
	[pagDescricao] [text] NULL,
	[pagRelacionadas] [text] NULL,
	[pagReferencia] [tinyint] NULL,
	[pagLayout] [int] NULL,
	[pagEscondeTitulo] [tinyint] NULL,
	[pagHTML] [tinyint] NULL,
	[pagTipoAlternativo] [tinyint] NULL,
	[pagAlternativo] [varchar](100) NULL,
	[pagArquivo1] [varchar](200) NULL,
	[pagArquivo2] [varchar](200) NULL,
	[pagArquivo3] [varchar](200) NULL,
	[pagArquivo4] [varchar](200) NULL,
	[pagArquivo4Legenda] [varchar](200) NULL,
	[pagArquivo4Full] [varchar](max) NULL,
	[pagArquivo4Show] [tinyint] NULL,
	[pagVideo] [varchar](200) NULL,
	[pagVideoShow] [tinyint] NULL,
	[pagPalavrasChave] [varchar](1000) NULL,
	[pagTipoRelacionamento] [tinyint] NULL,
	[pagInicioCapa] [datetime] NULL,
	[pagFimCapa] [datetime] NULL,
	[pagInicio] [datetime] NULL,
	[pagFim] [datetime] NULL,
	[pagTipo] [varchar](50) NULL,
	[pagDataReferencia] [datetime] NULL,
	[pagFonte] [int] NULL,
	[pagMapaSite] [tinyint] NULL,
	[pagNumero] [varchar](50) NULL,
	[pagAbertura] [datetime] NULL,
	[pagPublicacaoVezes] [int] NULL,
	[pagPublicacao] [datetime] NULL,
	[pagSiteMaps] [datetime] NULL,
	[pagNewsSiteMaps] [datetime] NULL,
	[pagURLList] [datetime] NULL,
	[pagSite] [int] NULL,
	[pagSitePrefix] [char](1) NULL,
	[pagURL] [varchar](255) NULL,
	[pagURLID] [numeric](19, 0) NULL,
	[pagURLIDComp] [varchar](50) NULL,
	[pagAprovada] [tinyint] NULL CONSTRAINT [DF_pubPaginas_pagAprovada]  DEFAULT ((0)),
	[pagInclusao] [datetime] NOT NULL,
	[pagAlteracao] [datetime] NULL,
	[pagAtivo] [tinyint] NOT NULL,
	[pagVisualizacao] [datetime] NULL,
	[pagVisualizacaoVezes] [int] NULL CONSTRAINT [DF_pubPaginas_pagVisualizacaoVezes]  DEFAULT ((0)),
	[pagInclusaoUsuario] [int] NULL,
	[pagAlteracaoUsuario] [int] NULL,
	[pagArquivoPasta] [varchar](255) NULL,
	[pagArquivoNome] [varchar](255) NULL,
	[pagTemplatePagina] [varchar](200) NULL,
	[pagTemplateUnico] [tinyint] NULL,
	[pagWAPNome] [varchar](30) NULL,
	[pagWAPTexto] [varchar](50) NULL,
	[pagMetaDescription] [varchar](10) NULL,
	[pagMetaKeywords] [varchar](200) NULL,
	[pagMetaRobots] [varchar](20) NULL,
	[pagMetaRevisitAfter] [varchar](20) NULL,
	[pagMetaRating] [varchar](20) NULL,
	[pagMetaOthers] [varchar](200) NULL,
	[pagMenu] [tinyint] NULL,
	[pagMenuReferencia] [int] NULL,
	[pagMenuID] [varchar](50) NULL,
	[pagFixa] [tinyint] NULL,
	[pagExclusao] [datetime] NULL,
	[pagExclusaoUsuario] [int] NULL,
	[pagTitleTag] [varchar](200) NULL,
	[pagAutor] [int] NULL,
	[pagAcessoRestrito] [tinyint] NULL,
	[pagAcessoNivel] [int] NULL,
	[pagHome] [tinyint] NULL,
	[pagPalavra] [tinyint] NULL,
	[pagLogin] [tinyint] NULL,
	[pagArquivoNomeOld] [varchar](255) NULL,
	[pagArquivoPastaOld] [varchar](255) NULL,
 CONSTRAINT [PK_pubPaginas] PRIMARY KEY CLUSTERED 
(
	[pagCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubPaginasFixa] ON [dbo].[pubPaginas] 
(
	[pagFixa] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPaginasInclusao] ON [dbo].[pubPaginas] 
(
	[pagInclusao] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPaginasNome] ON [dbo].[pubPaginas] 
(
	[pagNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPaginasPai] ON [dbo].[pubPaginas] 
(
	[pagPai] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPaginasPublicacao] ON [dbo].[pubPaginas] 
(
	[pagPublicacao] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPaginasSiteMaps] ON [dbo].[pubPaginas] 
(
	[pagSiteMaps] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPaginasTitulo] ON [dbo].[pubPaginas] 
(
	[pagTitulo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubQualificacaoTipo]    Script Date: 09/05/2010 01:13:43 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubQualificacaoTipo](
	[qutCodigo] [int] IDENTITY(1,1) NOT NULL,
	[qutNome] [varchar](50) NOT NULL,
	[qutPergunta] [varchar](100) NOT NULL,
	[qutComentario] [varchar](100) NOT NULL,
	[qutMinimo] [varchar](50) NOT NULL,
	[qutMaximo] [varchar](50) NOT NULL,
	[qutInclusao] [datetime] NOT NULL,
	[qutAlteracao] [datetime] NULL,
	[qutAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubQualificacaoTipo] PRIMARY KEY CLUSTERED 
(
	[qutCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubQualificacaoTipoNome] ON [dbo].[pubQualificacaoTipo] 
(
	[qutNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubSession]    Script Date: 09/05/2010 01:14:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubSession](
	[sesCodigo] [int] IDENTITY(1,1) NOT NULL,
	[sesInicio] [datetime] NOT NULL CONSTRAINT [DF_pubSession_sesInicio]  DEFAULT (getdate()),
	[sesFim] [datetime] NULL,
	[sesIP] [varchar](24) NOT NULL,
	[sesSessionID] [varchar](20) NULL,
	[sesHTTPReferer] [varchar](255) NULL,
 CONSTRAINT [PK_pubSession] PRIMARY KEY CLUSTERED 
(
	[sesCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pubSessionLogTipo]    Script Date: 09/05/2010 01:15:01 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubSessionLogTipo](
	[sltCodigo] [int] IDENTITY(1,1) NOT NULL,
	[sltSigla] [varchar](20) NULL,
	[sltDescricao] [varchar](255) NULL,
	[sltInclusao] [datetime] NOT NULL CONSTRAINT [DF_pubSessionLogTipo_sltInclusao]  DEFAULT (getdate()),
	[sltAlteracao] [datetime] NULL,
	[sltAtivo] [tinyint] NOT NULL CONSTRAINT [DF_pubSessionLogTipo_sltAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_pubSessionLogTipo] PRIMARY KEY CLUSTERED 
(
	[sltCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pubComentariosPalavras]    Script Date: 09/05/2010 00:58:13 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubComentariosPalavras](
	[copCodigo] [int] IDENTITY(1,1) NOT NULL,
	[copNome] [varchar](50) NOT NULL,
	[copFrase] [tinyint] NULL,
	[copInclusao] [varchar](50) NOT NULL CONSTRAINT [DF_pubComentariosPalavras_copInclusao]  DEFAULT (getdate()),
	[copAtivo] [tinyint] NULL CONSTRAINT [DF_pubComentariosPalavras_copAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_pubComentariosPalavras] PRIMARY KEY CLUSTERED 
(
	[copCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubComentariosPalavrasFrase] ON [dbo].[pubComentariosPalavras] 
(
	[copFrase] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubComentariosPalavrasNome] ON [dbo].[pubComentariosPalavras] 
(
	[copNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubUsuarioLogAcao]    Script Date: 09/05/2010 01:18:16 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubUsuarioLogAcao](
	[ulaCodigo] [int] IDENTITY(1,1) NOT NULL,
	[ulaSigla] [varchar](20) NOT NULL,
	[ulaNome] [varchar](50) NOT NULL,
	[ulaDescricao] [varchar](500) NULL,
	[ulaInclusao] [datetime] NOT NULL,
	[ulaAlteracao] [datetime] NULL,
	[ulaAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubUsuarioLogAcao] PRIMARY KEY CLUSTERED 
(
	[ulaCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioLogAcaoNome] ON [dbo].[pubUsuarioLogAcao] 
(
	[ulaNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioLogAcaoSigla] ON [dbo].[pubUsuarioLogAcao] 
(
	[ulaSigla] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubUsuarioPais]    Script Date: 09/05/2010 01:18:39 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubUsuarioPais](
	[usaCodigo] [int] IDENTITY(1,1) NOT NULL,
	[usaSigla] [varchar](2) NULL,
	[usaSigla3] [varchar](3) NULL,
	[usaNome] [varchar](40) NULL,
	[usaInclusao] [datetime] NOT NULL,
	[usaAlteracao] [datetime] NULL,
	[usaAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubUsuarioPais] PRIMARY KEY CLUSTERED 
(
	[usaCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioPaisNome] ON [dbo].[pubUsuarioPais] 
(
	[usaNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubUsuarioProfissao]    Script Date: 09/05/2010 01:19:05 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubUsuarioProfissao](
	[uspCodigo] [int] IDENTITY(1,1) NOT NULL,
	[uspSigla] [varchar](50) NOT NULL,
	[uspNome] [varchar](50) NOT NULL,
	[uspDescricao] [varchar](255) NULL,
	[uspInclusao] [datetime] NOT NULL,
	[uspAlteracao] [datetime] NULL,
	[uspAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubUsuarioProfissao] PRIMARY KEY CLUSTERED 
(
	[uspCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioProfissaoNome] ON [dbo].[pubUsuarioProfissao] 
(
	[uspNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioProfissaoSigla] ON [dbo].[pubUsuarioProfissao] 
(
	[uspSigla] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubUsuarioSexo]    Script Date: 09/05/2010 01:19:28 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubUsuarioSexo](
	[ussCodigo] [int] IDENTITY(1,1) NOT NULL,
	[ussSigla] [varchar](1) NULL,
	[ussNome] [varchar](20) NULL,
	[ussInclusao] [datetime] NOT NULL,
	[ussAlteracao] [datetime] NULL,
	[ussAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubUsuarioSexo] PRIMARY KEY CLUSTERED 
(
	[ussCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioSexoNome] ON [dbo].[pubUsuarioSexo] 
(
	[ussNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioSexoSigla] ON [dbo].[pubUsuarioSexo] 
(
	[ussSigla] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubUsuarioTipo]    Script Date: 09/05/2010 01:19:52 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubUsuarioTipo](
	[ustCodigo] [int] IDENTITY(1,1) NOT NULL,
	[ustSigla] [varchar](50) NOT NULL,
	[ustNome] [varchar](50) NOT NULL,
	[ustDescricao] [varchar](255) NULL,
	[ustInclusao] [datetime] NOT NULL,
	[ustAlteracao] [datetime] NULL,
	[ustAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubUsuarioTipo] PRIMARY KEY CLUSTERED 
(
	[ustCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioTipoSigla] ON [dbo].[pubUsuarioTipo] 
(
	[ustSigla] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubUsuarioUF]    Script Date: 09/05/2010 01:20:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubUsuarioUF](
	[usfCodigo] [int] IDENTITY(1,1) NOT NULL,
	[usfSigla] [varchar](2) NULL,
	[usfNome] [varchar](40) NULL,
	[usfInclusao] [datetime] NOT NULL,
	[usfAlteracao] [datetime] NULL,
	[usfAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubUsuarioUF] PRIMARY KEY CLUSTERED 
(
	[usfCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pubComentarios]    Script Date: 09/05/2010 00:57:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubComentarios](
	[comCodigo] [int] IDENTITY(1,1) NOT NULL,
	[comPagina] [int] NOT NULL,
	[comUsuario] [int] NULL,
	[comOrigem] [int] NULL,
	[comNome] [varchar](255) NOT NULL,
	[comEMail] [varchar](255) NOT NULL,
	[comURL] [varchar](255) NULL,
	[comCidade] [varchar](50) NULL,
	[comEstado] [varchar](2) NULL,
	[comAtividade] [varchar](255) NULL,
	[comEMailMostrar] [tinyint] NULL,
	[comComentario] [varchar](1000) NOT NULL,
	[comValidado] [datetime] NULL,
	[comNotificar] [tinyint] NULL,
	[comModerado] [varchar](1000) NULL,
	[comIP] [varchar](16) NOT NULL,
	[comSessao] [int] NULL,
	[comInclusao] [datetime] NOT NULL CONSTRAINT [DF_pubComentarios_comInclusao]  DEFAULT (getdate()),
	[comAtivo] [tinyint] NOT NULL CONSTRAINT [DF_pubComentarios_comAtivo]  DEFAULT ((0)),
 CONSTRAINT [PK_pubComentarios] PRIMARY KEY CLUSTERED 
(
	[comCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubComentariosEMail] ON [dbo].[pubComentarios] 
(
	[comEMail] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubComentariosInclusao] ON [dbo].[pubComentarios] 
(
	[comInclusao] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubComentariosNotificar] ON [dbo].[pubComentarios] 
(
	[comNotificar] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubComentariosPagina] ON [dbo].[pubComentarios] 
(
	[comPagina] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubPaginasVisualizacoes]    Script Date: 09/05/2010 01:09:04 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[pubPaginasVisualizacoes](
	[puvCodigo] [int] IDENTITY(1,1) NOT NULL,
	[puvPagina] [int] NULL,
	[puvAno] [int] NULL CONSTRAINT [DF_pubPaginasVisualizacoes_puvAno]  DEFAULT (datepart(year,getdate())),
	[puvMes] [tinyint] NULL CONSTRAINT [DF_pubPaginasVisualizacoes_puvMes]  DEFAULT (datepart(month,getdate())),
	[puvDia] [tinyint] NULL CONSTRAINT [DF_pubPaginasVisualizacoes_puvDia]  DEFAULT (datepart(day,getdate())),
	[puvHora] [tinyint] NULL CONSTRAINT [DF_pubPaginasVisualizacoes_puvHora]  DEFAULT (datename(hour,getdate())),
	[puvQuarter] [tinyint] NULL CONSTRAINT [DF_pubPaginasVisualizacoes_puvQuarter]  DEFAULT (datename(quarter,getdate())),
	[puvDayOfYear] [int] NULL CONSTRAINT [DF_pubPaginasVisualizacoes_puvDayOfYear]  DEFAULT (datename(dayofyear,getdate())),
	[puvWeek] [tinyint] NULL CONSTRAINT [DF_pubPaginasVisualizacoes_puvWeek]  DEFAULT (datename(week,getdate())),
	[puvWeekDay] [tinyint] NULL,
	[puvQuantidade] [int] NULL CONSTRAINT [DF_pubPaginasVisualizacoes_puvQuantidade]  DEFAULT ((0)),
	[puvInclusao] [datetime] NULL CONSTRAINT [DF_pubPaginasVisualizacoes_puvInclusao]  DEFAULT (getdate()),
 CONSTRAINT [PK_pubPaginasVisualizacoes] PRIMARY KEY CLUSTERED 
(
	[puvCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_Table_Ano] ON [dbo].[pubPaginasVisualizacoes] 
(
	[puvPagina] ASC,
	[puvAno] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_Table_AnoMes] ON [dbo].[pubPaginasVisualizacoes] 
(
	[puvPagina] ASC,
	[puvAno] ASC,
	[puvMes] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_Table_AnoMesDia] ON [dbo].[pubPaginasVisualizacoes] 
(
	[puvPagina] ASC,
	[puvAno] ASC,
	[puvMes] ASC,
	[puvDia] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_Table_AnoMesDiaHora] ON [dbo].[pubPaginasVisualizacoes] 
(
	[puvPagina] ASC,
	[puvAno] ASC,
	[puvMes] ASC,
	[puvDia] ASC,
	[puvHora] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubUsuarioEscolaridade]    Script Date: 09/05/2010 01:17:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubUsuarioEscolaridade](
	[useCodigo] [int] IDENTITY(1,1) NOT NULL,
	[useSigla] [varchar](20) NOT NULL,
	[useNome] [varchar](50) NOT NULL,
	[useDescricao] [varchar](255) NULL,
	[useInclusao] [datetime] NOT NULL,
	[useAlteracao] [datetime] NULL,
	[useAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubUsuarioEscolaridade] PRIMARY KEY CLUSTERED 
(
	[useCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioEscolaridadeNome] ON [dbo].[pubUsuarioEscolaridade] 
(
	[useNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioEscolaridadeSigla] ON [dbo].[pubUsuarioEscolaridade] 
(
	[useSigla] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[livCategoria]    Script Date: 09/05/2010 00:52:01 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[livCategoria](
	[catCodigo] [int] IDENTITY(1,1) NOT NULL,
	[catSigla] [varchar](20) NULL,
	[catNome] [varchar](50) NOT NULL,
	[catInclusao] [datetime] NOT NULL CONSTRAINT [DF_livCategoria_catInclusao]  DEFAULT (getdate()),
	[catAlteracao] [datetime] NULL,
	[catAtivo] [tinyint] NOT NULL CONSTRAINT [DF_livCategoria_catAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_livCategoria] PRIMARY KEY CLUSTERED 
(
	[catCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_livCategoriaNome] ON [dbo].[livCategoria] 
(
	[catNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_livCategoriaSigla] ON [dbo].[livCategoria] 
(
	[catSigla] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubFaleConoscoMensagens]    Script Date: 09/05/2010 01:00:49 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubFaleConoscoMensagens](
	[fcmCodigo] [int] IDENTITY(1,1) NOT NULL,
	[fcmSetor] [varchar](30) NULL,
	[fcmSetorEMail] [varchar](50) NULL,
	[fcmFazerUma] [varchar](30) NULL,
	[fcmNome] [varchar](50) NULL,
	[fcmEMail] [varchar](50) NULL,
	[fcmEndereco] [varchar](50) NULL,
	[fcmComplemento] [varchar](50) NULL,
	[fcmCidade] [varchar](50) NULL,
	[fcmUF] [varchar](2) NULL,
	[fcmCEP] [varchar](9) NULL,
	[fcmEmpresa] [varchar](50) NULL,
	[fcmTelefone] [varchar](20) NULL,
	[fcmFax] [varchar](20) NULL,
	[fcmSoube] [varchar](50) NULL,
	[fcmFaixa] [varchar](50) NULL,
	[fcmOpniao] [varchar](50) NULL,
	[fcmVoceE] [varchar](50) NULL,
	[fcmMensagem] [text] NULL,
	[fcmIP] [varchar](20) NULL,
	[fcmRetorno] [text] NULL,
	[fcmInclusao] [datetime] NULL,
	[fcmAlteracao] [datetime] NULL,
	[fcmAtivo] [tinyint] NULL,
 CONSTRAINT [PK_pubFaleConoscoMensagens] PRIMARY KEY CLUSTERED 
(
	[fcmCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[logIP]    Script Date: 09/05/2010 00:55:40 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[logIP](
	[ipaCodigo] [int] IDENTITY(1,1) NOT NULL,
	[ipaIP] [varchar](23) NULL,
	[ipaIP1] [tinyint] NULL CONSTRAINT [DF_logIP_ipaIP1]  DEFAULT ((0)),
	[ipaIP2] [tinyint] NULL CONSTRAINT [DF_logIP_ipaIP2]  DEFAULT ((0)),
	[ipaIP3] [tinyint] NULL CONSTRAINT [DF_logIP]  DEFAULT ((0)),
	[ipaIP4] [tinyint] NULL CONSTRAINT [DF_logIP_1]  DEFAULT ((0)),
	[ipaIP5] [tinyint] NULL CONSTRAINT [DF_logIP_2]  DEFAULT ((0)),
	[ipaIP6] [tinyint] NULL CONSTRAINT [DF_logIP_3]  DEFAULT ((0)),
	[ipaIPReverso] [varchar](255) NULL,
	[ipaInclusao] [datetime] NULL CONSTRAINT [DF_logIP_ipaInclusao]  DEFAULT (getdate()),
 CONSTRAINT [PK_logIP] PRIMARY KEY CLUSTERED 
(
	[ipaCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_logIPIP] ON [dbo].[logIP] 
(
	[ipaIP] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPIP1] ON [dbo].[logIP] 
(
	[ipaIP1] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPIP2] ON [dbo].[logIP] 
(
	[ipaIP2] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPIP3] ON [dbo].[logIP] 
(
	[ipaIP3] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPIP4] ON [dbo].[logIP] 
(
	[ipaIP4] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPIP5] ON [dbo].[logIP] 
(
	[ipaIP5] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPIP6] ON [dbo].[logIP] 
(
	[ipaIP6] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPIPAll] ON [dbo].[logIP] 
(
	[ipaIP1] ASC,
	[ipaIP2] ASC,
	[ipaIP3] ASC,
	[ipaIP4] ASC,
	[ipaIP5] ASC,
	[ipaIP6] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[livEditora]    Script Date: 09/05/2010 00:52:53 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[livEditora](
	[ediCodigo] [int] IDENTITY(1,1) NOT NULL,
	[ediSigla] [varchar](50) NULL,
	[ediNome] [varchar](50) NOT NULL,
	[ediInclusao] [datetime] NOT NULL CONSTRAINT [DF_livEditoria_ediInclusao]  DEFAULT (getdate()),
	[ediAlteracao] [datetime] NULL,
	[ediAtivo] [tinyint] NOT NULL CONSTRAINT [DF_livEditoria_ediAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_livEditoria] PRIMARY KEY CLUSTERED 
(
	[ediCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_livEditoraSigla] ON [dbo].[livEditora] 
(
	[ediSigla] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_livEditoriaNome] ON [dbo].[livEditora] 
(
	[ediNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubFonte]    Script Date: 09/05/2010 01:01:34 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubFonte](
	[fonCodigo] [int] IDENTITY(1,1) NOT NULL,
	[fonSigla] [varchar](20) NULL,
	[fonNome] [varchar](50) NOT NULL,
	[fonDescricao] [varchar](250) NULL,
	[fonInclusao] [datetime] NOT NULL,
	[fonAlteracao] [datetime] NULL,
	[fonAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubFonte] PRIMARY KEY CLUSTERED 
(
	[fonCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[livColecao]    Script Date: 09/05/2010 00:52:26 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[livColecao](
	[colCodigo] [int] IDENTITY(1,1) NOT NULL,
	[colSigla] [varchar](255) NULL,
	[colNome] [varchar](255) NULL,
	[colDescricao] [varchar](1000) NULL,
	[colCollectionId] [int] NULL,
	[colInclusao] [datetime] NULL CONSTRAINT [DF_livColecao_colInclusao]  DEFAULT (getdate()),
	[colAlteracao] [datetime] NULL,
	[colAtivo] [tinyint] NOT NULL CONSTRAINT [DF_livColecao_colAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_livColecao] PRIMARY KEY CLUSTERED 
(
	[colCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pubFaleConoscoSetor]    Script Date: 09/05/2010 01:01:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubFaleConoscoSetor](
	[fcsCodigo] [int] IDENTITY(1,1) NOT NULL,
	[fcsNome] [varchar](30) NOT NULL,
	[fcsEMail] [varchar](50) NOT NULL,
	[fcsInclusao] [datetime] NOT NULL,
	[fcsAlteracao] [datetime] NULL,
	[fcsAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubFaleConoscoSetor] PRIMARY KEY CLUSTERED 
(
	[fcsCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubFaleConoscoSetorNome] ON [dbo].[pubFaleConoscoSetor] 
(
	[fcsNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[livLivro]    Script Date: 09/05/2010 00:54:28 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[livLivro](
	[livCodigo] [int] IDENTITY(1,1) NOT NULL,
	[livCategoria] [int] NULL,
	[livHome] [tinyint] NULL,
	[livDestaque] [tinyint] NULL,
	[livPromocao] [tinyint] NULL,
	[livGratisSudoeste] [tinyint] NULL,
	[livGratisBrasil] [tinyint] NULL,
	[livNome] [varchar](255) NOT NULL,
	[livDescricao] [text] NULL,
	[livISBN] [varchar](20) NULL,
	[livAno] [int] NULL,
	[livEdicao] [int] NULL,
	[livPaginas] [int] NULL,
	[livAcabamento] [varchar](50) NULL,
	[livFormato] [varchar](50) NULL,
	[livEditora] [int] NULL,
	[livColecao] [int] NULL,
	[livComplemento] [varchar](255) NULL,
	[livComplementoEdicao] [varchar](255) NULL,
	[livAutor] [int] NULL,
	[livImagem] [varchar](255) NULL,
	[livProdID] [int] NULL,
	[livCatID] [int] NULL,
	[livArquivoNome] [varchar](200) NULL,
	[livValorDe] [money] NULL,
	[livValorPor] [money] NULL,
	[livComentarios] [int] NULL CONSTRAINT [DF_livLivro_livComentarios]  DEFAULT ((0)),
	[livVisualizacoes] [int] NULL CONSTRAINT [DF_livLivro_livVisualizacoes]  DEFAULT ((0)),
	[livDisponivel] [tinyint] NULL CONSTRAINT [DF_livLivro_livDisponivel]  DEFAULT ((1)),
	[livSiteMaps] [tinyint] NULL,
	[livInclusao] [datetime] NOT NULL CONSTRAINT [DF_livLivro_livInclusao]  DEFAULT (getdate()),
	[livAlteracao] [datetime] NULL,
	[livAtivo] [tinyint] NOT NULL CONSTRAINT [DF_livLivro_livAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_livLivro] PRIMARY KEY CLUSTERED 
(
	[livCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_livLivroAno] ON [dbo].[livLivro] 
(
	[livAno] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_livLivroArquivoNome] ON [dbo].[livLivro] 
(
	[livArquivoNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_livLivroISBN] ON [dbo].[livLivro] 
(
	[livISBN] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_livLivroNome] ON [dbo].[livLivro] 
(
	[livNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_livLivroProdID] ON [dbo].[livLivro] 
(
	[livProdID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubQualifacaoLog]    Script Date: 09/05/2010 01:12:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubQualifacaoLog](
	[qulCodigo] [int] IDENTITY(1,1) NOT NULL,
	[qulQualificacao] [int] NULL,
	[qulSelecao] [tinyint] NULL,
	[qulComentario] [varchar](500) NULL,
	[qulIP] [varchar](16) NULL,
	[qulSessao] [int] NULL,
	[qulInclusao] [datetime] NULL,
 CONSTRAINT [PK_pubQualifacaoLog] PRIMARY KEY CLUSTERED 
(
	[qulCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubQualifacaoLogInclusao] ON [dbo].[pubQualifacaoLog] 
(
	[qulInclusao] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubQualifacaoLogQualificacao] ON [dbo].[pubQualifacaoLog] 
(
	[qulQualificacao] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[logIPDW]    Script Date: 09/05/2010 00:56:18 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[logIPDW](
	[idwCodigo] [int] IDENTITY(1,1) NOT NULL,
	[idwIP] [int] NULL,
	[idwAno] [int] NULL,
	[idwMes] [tinyint] NULL,
	[idwDia] [tinyint] NULL,
	[idwQuantidade] [int] NULL CONSTRAINT [DF_logIPDW_idwQuantidade]  DEFAULT ((0)),
 CONSTRAINT [PK_logIPDW] PRIMARY KEY CLUSTERED 
(
	[idwCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPDWAno] ON [dbo].[logIPDW] 
(
	[idwAno] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPDWAnoMes] ON [dbo].[logIPDW] 
(
	[idwAno] ASC,
	[idwMes] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPDWAnoMesDia] ON [dbo].[logIPDW] 
(
	[idwAno] ASC,
	[idwMes] ASC,
	[idwDia] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPDWDia] ON [dbo].[logIPDW] 
(
	[idwDia] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPDWMes] ON [dbo].[logIPDW] 
(
	[idwMes] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_logIPDWQuantidade] ON [dbo].[logIPDW] 
(
	[idwQuantidade] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubUsuarioLog]    Script Date: 09/05/2010 01:17:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubUsuarioLog](
	[uslCodigo] [int] IDENTITY(1,1) NOT NULL,
	[uslUsuario] [int] NOT NULL,
	[uslAcao] [int] NOT NULL,
	[uslComentario] [varchar](50) NULL,
	[uslSessionID] [varchar](30) NULL,
	[uslLogin] [datetime] NOT NULL,
	[uslLogout] [datetime] NULL,
	[uslIP] [varchar](16) NOT NULL,
 CONSTRAINT [PK_pubUsuarioLog] PRIMARY KEY CLUSTERED 
(
	[uslCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioLogSessionID] ON [dbo].[pubUsuarioLog] 
(
	[uslSessionID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioLogUsuario] ON [dbo].[pubUsuarioLog] 
(
	[uslUsuario] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubEnquete]    Script Date: 09/05/2010 00:59:09 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubEnquete](
	[enqCodigo] [int] IDENTITY(1,1) NOT NULL,
	[enqAssunto] [int] NULL,
	[enqPai] [int] NULL,
	[enqPagina] [int] NULL,
	[enqPergunta] [varchar](255) NOT NULL,
	[enqTipo] [tinyint] NOT NULL,
	[enqDataInicio] [datetime] NULL,
	[enqDataFim] [datetime] NULL,
	[enqHoraInicio] [varchar](8) NULL,
	[enqHoraFim] [varchar](8) NULL,
	[enqRecomenda] [tinyint] NULL,
	[enqResultado] [tinyint] NULL,
	[enqQuantidade] [tinyint] NULL,
	[enqPerguntas] [varchar](800) NOT NULL,
	[enqResposta] [varchar](255) NULL,
	[enqRodapeEnquete] [varchar](255) NULL,
	[enqRodapeResultado] [varchar](255) NULL,
	[enqInclusao] [datetime] NOT NULL,
	[enqAlteracao] [datetime] NULL,
	[enqAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubEnquete] PRIMARY KEY NONCLUSTERED 
(
	[enqCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubEnqueteAssunto] ON [dbo].[pubEnquete] 
(
	[enqAssunto] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubEnqueteDataInicio] ON [dbo].[pubEnquete] 
(
	[enqDataInicio] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubEnqueteHoraInicio] ON [dbo].[pubEnquete] 
(
	[enqHoraInicio] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubImagem]    Script Date: 09/05/2010 01:02:26 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubImagem](
	[imgCodigo] [int] IDENTITY(1,1) NOT NULL,
	[imgCatalogo] [int] NULL,
	[imgNome] [varchar](50) NOT NULL,
	[imgResumo] [varchar](250) NULL,
	[imgDescricao] [varchar](1000) NULL,
	[imgPalavrasChave] [varchar](100) NULL,
	[imgIcone] [varchar](100) NOT NULL,
	[imgApresentacao] [varchar](100) NOT NULL,
	[imgOriginal] [varchar](100) NULL,
	[imgLargura] [int] NULL,
	[imgAltura] [int] NULL,
	[imgTamanho] [int] NULL,
	[imgCriacao] [datetime] NULL,
	[imgVisualizacao] [int] NULL,
	[imgVisualizacaoData] [datetime] NULL,
	[imgDownload] [int] NULL,
	[imgDownloadData] [datetime] NULL,
	[imgInclusao] [datetime] NOT NULL CONSTRAINT [DF_pubImagem_imgInclusao]  DEFAULT (getdate()),
	[imgAlteracao] [datetime] NULL,
	[imgAtivo] [tinyint] NOT NULL CONSTRAINT [DF_pubImagem_imgAtivo]  DEFAULT ((1)),
 CONSTRAINT [PK_pubImagem] PRIMARY KEY CLUSTERED 
(
	[imgCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubImagemNome] ON [dbo].[pubImagem] 
(
	[imgNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubOperador]    Script Date: 09/05/2010 01:04:26 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubOperador](
	[opeCodigo] [int] IDENTITY(1,1) NOT NULL,
	[opeTipo] [int] NULL,
	[opeNome] [varchar](50) NOT NULL,
	[opeLogin] [varchar](20) NOT NULL,
	[opeSenha] [varchar](20) NOT NULL,
	[opeEMail] [varchar](50) NULL,
	[opePublica] [tinyint] NULL CONSTRAINT [DF_pubOperador_opePublica]  DEFAULT ((0)),
	[opeEdita] [tinyint] NULL CONSTRAINT [DF_pubOperador_opeEdita]  DEFAULT ((0)),
	[opeEscreve] [tinyint] NULL CONSTRAINT [DF_pubOperador_opeEscreve]  DEFAULT ((0)),
	[opeAdministra] [tinyint] NULL CONSTRAINT [DF_pubOperador_opeAdministra]  DEFAULT ((0)),
	[opeInclusao] [datetime] NOT NULL,
	[opeAlteracao] [datetime] NULL,
	[opeAtivo] [tinyint] NOT NULL,
	[opeLoginVezes] [int] NULL CONSTRAINT [DF_pubOperador_opeLoginVezes]  DEFAULT ((0)),
	[opeLoginLast] [datetime] NULL,
	[opeLoginLastIP] [varchar](20) NULL,
	[opeSenhaVezes] [int] NULL CONSTRAINT [DF_pubOperador_opeSenhaVezes]  DEFAULT ((0)),
	[opeSenhaLast] [datetime] NULL,
	[opeSenhaLastIP] [varchar](20) NULL,
	[opeSenhaErroVezes] [int] NULL CONSTRAINT [DF_pubOperador_opeSenhaErroVezes]  DEFAULT ((0)),
	[opeSenhaErroVezesT] [int] NULL CONSTRAINT [DF_pubOperador_opeSenhaErroVezesT]  DEFAULT ((0)),
	[opeSenhaErro] [varchar](2) NULL,
	[opeSenhaErroLast] [datetime] NULL,
	[opeSenhaErroLastIP] [varchar](20) NULL,
 CONSTRAINT [PK_pubOperador] PRIMARY KEY CLUSTERED 
(
	[opeCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pubPalavraLog]    Script Date: 09/05/2010 01:10:42 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubPalavraLog](
	[ploCodigo] [int] IDENTITY(1,1) NOT NULL,
	[ploPalavra] [int] NULL,
	[ploSearchEngine] [tinyint] NULL,
	[ploURL] [nvarchar](1000) NULL CONSTRAINT [DF_pubPalavraLog_ploURL]  DEFAULT ((1)),
	[ploURLUTF8] [varchar](1000) NULL CONSTRAINT [DF_pubPalavraLog_ploURL1]  DEFAULT ((1)),
	[ploInclusao] [datetime] NULL CONSTRAINT [DF_pubPalavraLog_ploInclusao]  DEFAULT (getdate()),
 CONSTRAINT [PK_pubPalavraLog] PRIMARY KEY CLUSTERED 
(
	[ploCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraLogPalavra] ON [dbo].[pubPalavraLog] 
(
	[ploPalavra] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubPalavraPagina]    Script Date: 09/05/2010 01:11:06 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[pubPalavraPagina](
	[papCodigo] [int] IDENTITY(1,1) NOT NULL,
	[papPalavra] [int] NULL,
	[papPagina] [int] NULL,
	[papInclusao] [datetime] NULL CONSTRAINT [DF_pubPalavraPagina_papInclusao]  DEFAULT (getdate()),
 CONSTRAINT [PK_pubPalavraPagina] PRIMARY KEY CLUSTERED 
(
	[papCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraPaginaPagina] ON [dbo].[pubPalavraPagina] 
(
	[papPagina] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraPaginaPalavra] ON [dbo].[pubPalavraPagina] 
(
	[papPalavra] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubPalavraStats]    Script Date: 09/05/2010 01:11:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[pubPalavraStats](
	[pasCodigo] [int] IDENTITY(1,1) NOT NULL,
	[pasPalavra] [int] NULL,
	[pasAno] [int] NULL,
	[pasMes] [tinyint] NULL,
	[pasDia] [tinyint] NULL,
	[pasQuantidade] [int] NULL CONSTRAINT [DF_pubPalavraStats_pasQuantidade]  DEFAULT ((1)),
	[pasGoogle] [int] NULL CONSTRAINT [DF_pubPalavraStats_pasGoogle]  DEFAULT ((0)),
	[pasYahoo] [int] NULL CONSTRAINT [DF_pubPalavraStats_pasYahoo]  DEFAULT ((0)),
	[pasLive] [int] NULL CONSTRAINT [DF_pubPalavraStats_pasLive]  DEFAULT ((0)),
	[pasAltavista] [int] NULL CONSTRAINT [DF_pubPalavraStats_pasAltavista]  DEFAULT ((0)),
 CONSTRAINT [PK_pubPalavraStats] PRIMARY KEY CLUSTERED 
(
	[pasCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraStatsAno] ON [dbo].[pubPalavraStats] 
(
	[pasAno] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraStatsAnoMes] ON [dbo].[pubPalavraStats] 
(
	[pasAno] ASC,
	[pasMes] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraStatsAnoMesDia] ON [dbo].[pubPalavraStats] 
(
	[pasAno] ASC,
	[pasMes] ASC,
	[pasDia] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraStatsDia] ON [dbo].[pubPalavraStats] 
(
	[pasDia] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraStatsMes] ON [dbo].[pubPalavraStats] 
(
	[pasMes] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubPalavraStatsPalavra] ON [dbo].[pubPalavraStats] 
(
	[pasPalavra] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubQualificacao]    Script Date: 09/05/2010 01:13:13 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[pubQualificacao](
	[quaCodigo] [int] IDENTITY(1,1) NOT NULL,
	[quaTipo] [int] NULL,
	[quaPagina] [int] NOT NULL,
	[quaPessoas] [int] NULL,
	[quaValor1] [int] NULL,
	[quaValor2] [int] NULL,
	[quaValor3] [int] NULL,
	[quaValor4] [int] NULL,
	[quaValor5] [int] NULL,
	[quaValor6] [int] NULL,
	[quaValor7] [int] NULL,
	[quaValor8] [int] NULL,
	[quaValor9] [int] NULL,
	[quaInclusao] [datetime] NOT NULL,
	[quaAlteracao] [datetime] NULL,
	[quaAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubQualificacao] PRIMARY KEY CLUSTERED 
(
	[quaCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubQualificacaoPagina] ON [dbo].[pubQualificacao] 
(
	[quaPagina] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubQualificacaoTipo] ON [dbo].[pubQualificacao] 
(
	[quaTipo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubSessionLog]    Script Date: 09/05/2010 01:14:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubSessionLog](
	[selCodigo] [int] IDENTITY(1,1) NOT NULL,
	[selSession] [int] NULL,
	[selOperador] [int] NULL,
	[selOperadorNome] [varchar](50) NULL,
	[selTipo] [int] NULL CONSTRAINT [DF_pubSessionLog_selTipo]  DEFAULT ((0)),
	[selPrograma] [varchar](255) NULL,
	[selQueryString] [varchar](255) NULL,
	[selMensagem] [varchar](255) NULL,
	[selConteudo] [text] NULL,
	[selInclusao] [datetime] NOT NULL CONSTRAINT [DF_pubSessionLog_selInclusao]  DEFAULT (getdate()),
 CONSTRAINT [PK_pubSessionLog] PRIMARY KEY CLUSTERED 
(
	[selCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pubUsuario]    Script Date: 09/05/2010 01:16:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubUsuario](
	[usuCodigo] [int] IDENTITY(1,1) NOT NULL,
	[usuTipo] [int] NULL,
	[usuNome] [varchar](60) NOT NULL,
	[usuEndereco] [varchar](60) NULL,
	[usuComplemento] [varchar](60) NULL,
	[usuCidade] [varchar](50) NULL,
	[usuUF] [int] NULL,
	[usuCEP] [varchar](10) NULL,
	[usuPais] [int] NULL,
	[usuNascimento] [datetime] NOT NULL,
	[usuSexo] [int] NOT NULL,
	[usuEscolaridade] [int] NOT NULL,
	[usuProfissao] [int] NOT NULL,
	[usuCPF] [varchar](20) NOT NULL,
	[usuOAB] [varchar](20) NULL,
	[usuTelefone] [varchar](20) NULL,
	[usuFax] [varchar](20) NULL,
	[usuCelular] [varchar](20) NULL,
	[usuURL] [varchar](60) NULL,
	[usuComentarios] [varchar](500) NULL,
	[usuUserID] [varchar](100) NOT NULL,
	[usuPassword] [varchar](20) NOT NULL,
	[usuNoticias] [tinyint] NULL,
	[usuEstado] [tinyint] NULL,
	[usuConfirmacao] [datetime] NULL,
	[usuConfirmacaoIP] [varchar](16) NULL,
	[usuAprovacao] [datetime] NULL,
	[usuDesativacao] [datetime] NULL,
	[usuLogonUltimo] [datetime] NULL,
	[usuLogonIP] [varchar](16) NULL,
	[usuLogonUltimoErro] [datetime] NULL,
	[usuLogonErros] [int] NULL,
	[usuLogonErroIP] [varchar](16) NULL,
	[usuLogonBloqueio] [datetime] NULL,
	[usuObservacoes] [varchar](500) NULL,
	[usuInclusaoIP] [varchar](16) NOT NULL,
	[usuInclusao] [datetime] NOT NULL,
	[usuAlteracao] [datetime] NULL,
	[usuAtivo] [tinyint] NOT NULL,
 CONSTRAINT [PK_pubUsuario] PRIMARY KEY CLUSTERED 
(
	[usuCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioNome] ON [dbo].[pubUsuario] 
(
	[usuNome] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioOAB] ON [dbo].[pubUsuario] 
(
	[usuOAB] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
CREATE NONCLUSTERED INDEX [IX_pubUsuarioUserID] ON [dbo].[pubUsuario] 
(
	[usuUserID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[pubEnqueteResultado]    Script Date: 09/05/2010 00:59:56 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pubEnqueteResultado](
	[enrCodigo] [int] IDENTITY(1,1) NOT NULL,
	[enrEnquete] [int] NOT NULL,
	[enrSessao] [int] NOT NULL,
	[enrIP] [varchar](16) NOT NULL,
	[enrUsuario] [int] NULL,
	[enrResultado] [int] NOT NULL,
	[enrInclusao] [datetime] NOT NULL,
 CONSTRAINT [PK_pubEnqueteResultado] PRIMARY KEY CLUSTERED 
(
	[enrCodigo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
CREATE NONCLUSTERED INDEX [IX_pubEnqueteResultadoEnquete] ON [dbo].[pubEnqueteResultado] 
(
	[enrEnquete] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
GO
/****** Object:  ForeignKey [FK_livLivro_livAutor]    Script Date: 09/05/2010 00:54:32 ******/
ALTER TABLE [dbo].[livLivro]  WITH CHECK ADD  CONSTRAINT [FK_livLivro_livAutor] FOREIGN KEY([livAutor])
REFERENCES [dbo].[livAutor] ([autCodigo])
GO
ALTER TABLE [dbo].[livLivro] CHECK CONSTRAINT [FK_livLivro_livAutor]
GO
/****** Object:  ForeignKey [FK_livLivro_livCategoria]    Script Date: 09/05/2010 00:54:34 ******/
ALTER TABLE [dbo].[livLivro]  WITH CHECK ADD  CONSTRAINT [FK_livLivro_livCategoria] FOREIGN KEY([livCategoria])
REFERENCES [dbo].[livCategoria] ([catCodigo])
GO
ALTER TABLE [dbo].[livLivro] CHECK CONSTRAINT [FK_livLivro_livCategoria]
GO
/****** Object:  ForeignKey [FK_livLivro_livColecao]    Script Date: 09/05/2010 00:54:37 ******/
ALTER TABLE [dbo].[livLivro]  WITH CHECK ADD  CONSTRAINT [FK_livLivro_livColecao] FOREIGN KEY([livColecao])
REFERENCES [dbo].[livColecao] ([colCodigo])
GO
ALTER TABLE [dbo].[livLivro] CHECK CONSTRAINT [FK_livLivro_livColecao]
GO
/****** Object:  ForeignKey [FK_livLivro_livEditora]    Script Date: 09/05/2010 00:54:39 ******/
ALTER TABLE [dbo].[livLivro]  WITH CHECK ADD  CONSTRAINT [FK_livLivro_livEditora] FOREIGN KEY([livEditora])
REFERENCES [dbo].[livEditora] ([ediCodigo])
GO
ALTER TABLE [dbo].[livLivro] CHECK CONSTRAINT [FK_livLivro_livEditora]
GO
/****** Object:  ForeignKey [FK_logIPDW_logIP]    Script Date: 09/05/2010 00:56:21 ******/
ALTER TABLE [dbo].[logIPDW]  WITH CHECK ADD  CONSTRAINT [FK_logIPDW_logIP] FOREIGN KEY([idwIP])
REFERENCES [dbo].[logIP] ([ipaCodigo])
GO
ALTER TABLE [dbo].[logIPDW] CHECK CONSTRAINT [FK_logIPDW_logIP]
GO
/****** Object:  ForeignKey [FK_pubEnquete_pubEnqueteAssunto]    Script Date: 09/05/2010 00:59:13 ******/
ALTER TABLE [dbo].[pubEnquete]  WITH CHECK ADD  CONSTRAINT [FK_pubEnquete_pubEnqueteAssunto] FOREIGN KEY([enqAssunto])
REFERENCES [dbo].[pubEnqueteAssunto] ([enaCodigo])
GO
ALTER TABLE [dbo].[pubEnquete] CHECK CONSTRAINT [FK_pubEnquete_pubEnqueteAssunto]
GO
/****** Object:  ForeignKey [FK_pubEnqueteResultado_pubEnquete]    Script Date: 09/05/2010 01:00:00 ******/
ALTER TABLE [dbo].[pubEnqueteResultado]  WITH CHECK ADD  CONSTRAINT [FK_pubEnqueteResultado_pubEnquete] FOREIGN KEY([enrEnquete])
REFERENCES [dbo].[pubEnquete] ([enqCodigo])
GO
ALTER TABLE [dbo].[pubEnqueteResultado] CHECK CONSTRAINT [FK_pubEnqueteResultado_pubEnquete]
GO
/****** Object:  ForeignKey [FK_pubImagem_pubImagemCatalogo]    Script Date: 09/05/2010 01:02:29 ******/
ALTER TABLE [dbo].[pubImagem]  WITH CHECK ADD  CONSTRAINT [FK_pubImagem_pubImagemCatalogo] FOREIGN KEY([imgCatalogo])
REFERENCES [dbo].[pubImagemCatalogo] ([imcCodigo])
GO
ALTER TABLE [dbo].[pubImagem] CHECK CONSTRAINT [FK_pubImagem_pubImagemCatalogo]
GO
/****** Object:  ForeignKey [FK_pubOperador_pubOperadorTipo]    Script Date: 09/05/2010 01:04:29 ******/
ALTER TABLE [dbo].[pubOperador]  WITH CHECK ADD  CONSTRAINT [FK_pubOperador_pubOperadorTipo] FOREIGN KEY([opeTipo])
REFERENCES [dbo].[pubOperadorTipo] ([optCodigo])
GO
ALTER TABLE [dbo].[pubOperador] CHECK CONSTRAINT [FK_pubOperador_pubOperadorTipo]
GO
/****** Object:  ForeignKey [FK_pubPalavraLog_pubPalavra]    Script Date: 09/05/2010 01:10:45 ******/
ALTER TABLE [dbo].[pubPalavraLog]  WITH CHECK ADD  CONSTRAINT [FK_pubPalavraLog_pubPalavra] FOREIGN KEY([ploPalavra])
REFERENCES [dbo].[pubPalavra] ([palCodigo])
GO
ALTER TABLE [dbo].[pubPalavraLog] CHECK CONSTRAINT [FK_pubPalavraLog_pubPalavra]
GO
/****** Object:  ForeignKey [FK_pubPalavraPagina_pubPalavra]    Script Date: 09/05/2010 01:11:09 ******/
ALTER TABLE [dbo].[pubPalavraPagina]  WITH CHECK ADD  CONSTRAINT [FK_pubPalavraPagina_pubPalavra] FOREIGN KEY([papPalavra])
REFERENCES [dbo].[pubPalavra] ([palCodigo])
GO
ALTER TABLE [dbo].[pubPalavraPagina] CHECK CONSTRAINT [FK_pubPalavraPagina_pubPalavra]
GO
/****** Object:  ForeignKey [FK_pubPalavraStats_pubPalavra]    Script Date: 09/05/2010 01:12:02 ******/
ALTER TABLE [dbo].[pubPalavraStats]  WITH CHECK ADD  CONSTRAINT [FK_pubPalavraStats_pubPalavra] FOREIGN KEY([pasPalavra])
REFERENCES [dbo].[pubPalavra] ([palCodigo])
GO
ALTER TABLE [dbo].[pubPalavraStats] CHECK CONSTRAINT [FK_pubPalavraStats_pubPalavra]
GO
/****** Object:  ForeignKey [FK_pubQualifacaoLog_pubQualificacao]    Script Date: 09/05/2010 01:12:30 ******/
ALTER TABLE [dbo].[pubQualifacaoLog]  WITH CHECK ADD  CONSTRAINT [FK_pubQualifacaoLog_pubQualificacao] FOREIGN KEY([qulQualificacao])
REFERENCES [dbo].[pubQualificacao] ([quaCodigo])
GO
ALTER TABLE [dbo].[pubQualifacaoLog] CHECK CONSTRAINT [FK_pubQualifacaoLog_pubQualificacao]
GO
/****** Object:  ForeignKey [FK_pubQualificacao_pubQualificacaoTipo]    Script Date: 09/05/2010 01:13:17 ******/
ALTER TABLE [dbo].[pubQualificacao]  WITH CHECK ADD  CONSTRAINT [FK_pubQualificacao_pubQualificacaoTipo] FOREIGN KEY([quaTipo])
REFERENCES [dbo].[pubQualificacaoTipo] ([qutCodigo])
GO
ALTER TABLE [dbo].[pubQualificacao] CHECK CONSTRAINT [FK_pubQualificacao_pubQualificacaoTipo]
GO
/****** Object:  ForeignKey [FK_pubSessionLog_pubOperador]    Script Date: 09/05/2010 01:14:36 ******/
ALTER TABLE [dbo].[pubSessionLog]  WITH CHECK ADD  CONSTRAINT [FK_pubSessionLog_pubOperador] FOREIGN KEY([selOperador])
REFERENCES [dbo].[pubOperador] ([opeCodigo])
GO
ALTER TABLE [dbo].[pubSessionLog] CHECK CONSTRAINT [FK_pubSessionLog_pubOperador]
GO
/****** Object:  ForeignKey [FK_pubSessionLog_pubSession]    Script Date: 09/05/2010 01:14:38 ******/
ALTER TABLE [dbo].[pubSessionLog]  WITH CHECK ADD  CONSTRAINT [FK_pubSessionLog_pubSession] FOREIGN KEY([selSession])
REFERENCES [dbo].[pubSession] ([sesCodigo])
GO
ALTER TABLE [dbo].[pubSessionLog] CHECK CONSTRAINT [FK_pubSessionLog_pubSession]
GO
/****** Object:  ForeignKey [FK_pubSessionLog_pubSessionLogTipo]    Script Date: 09/05/2010 01:14:41 ******/
ALTER TABLE [dbo].[pubSessionLog]  WITH CHECK ADD  CONSTRAINT [FK_pubSessionLog_pubSessionLogTipo] FOREIGN KEY([selTipo])
REFERENCES [dbo].[pubSessionLogTipo] ([sltCodigo])
GO
ALTER TABLE [dbo].[pubSessionLog] CHECK CONSTRAINT [FK_pubSessionLog_pubSessionLogTipo]
GO
/****** Object:  ForeignKey [FK_pubUsuario_pubUsuarioEscolaridade]    Script Date: 09/05/2010 01:16:39 ******/
ALTER TABLE [dbo].[pubUsuario]  WITH CHECK ADD  CONSTRAINT [FK_pubUsuario_pubUsuarioEscolaridade] FOREIGN KEY([usuEscolaridade])
REFERENCES [dbo].[pubUsuarioEscolaridade] ([useCodigo])
GO
ALTER TABLE [dbo].[pubUsuario] CHECK CONSTRAINT [FK_pubUsuario_pubUsuarioEscolaridade]
GO
/****** Object:  ForeignKey [FK_pubUsuario_pubUsuarioPais]    Script Date: 09/05/2010 01:16:42 ******/
ALTER TABLE [dbo].[pubUsuario]  WITH CHECK ADD  CONSTRAINT [FK_pubUsuario_pubUsuarioPais] FOREIGN KEY([usuPais])
REFERENCES [dbo].[pubUsuarioPais] ([usaCodigo])
GO
ALTER TABLE [dbo].[pubUsuario] CHECK CONSTRAINT [FK_pubUsuario_pubUsuarioPais]
GO
/****** Object:  ForeignKey [FK_pubUsuario_pubUsuarioProfissao]    Script Date: 09/05/2010 01:16:44 ******/
ALTER TABLE [dbo].[pubUsuario]  WITH CHECK ADD  CONSTRAINT [FK_pubUsuario_pubUsuarioProfissao] FOREIGN KEY([usuProfissao])
REFERENCES [dbo].[pubUsuarioProfissao] ([uspCodigo])
GO
ALTER TABLE [dbo].[pubUsuario] CHECK CONSTRAINT [FK_pubUsuario_pubUsuarioProfissao]
GO
/****** Object:  ForeignKey [FK_pubUsuario_pubUsuarioSexo]    Script Date: 09/05/2010 01:16:47 ******/
ALTER TABLE [dbo].[pubUsuario]  WITH CHECK ADD  CONSTRAINT [FK_pubUsuario_pubUsuarioSexo] FOREIGN KEY([usuSexo])
REFERENCES [dbo].[pubUsuarioSexo] ([ussCodigo])
GO
ALTER TABLE [dbo].[pubUsuario] CHECK CONSTRAINT [FK_pubUsuario_pubUsuarioSexo]
GO
/****** Object:  ForeignKey [FK_pubUsuario_pubUsuarioTipo]    Script Date: 09/05/2010 01:16:49 ******/
ALTER TABLE [dbo].[pubUsuario]  WITH CHECK ADD  CONSTRAINT [FK_pubUsuario_pubUsuarioTipo] FOREIGN KEY([usuTipo])
REFERENCES [dbo].[pubUsuarioTipo] ([ustCodigo])
GO
ALTER TABLE [dbo].[pubUsuario] CHECK CONSTRAINT [FK_pubUsuario_pubUsuarioTipo]
GO
/****** Object:  ForeignKey [FK_pubUsuario_pubUsuarioUF]    Script Date: 09/05/2010 01:16:52 ******/
ALTER TABLE [dbo].[pubUsuario]  WITH CHECK ADD  CONSTRAINT [FK_pubUsuario_pubUsuarioUF] FOREIGN KEY([usuUF])
REFERENCES [dbo].[pubUsuarioUF] ([usfCodigo])
GO
ALTER TABLE [dbo].[pubUsuario] CHECK CONSTRAINT [FK_pubUsuario_pubUsuarioUF]
GO
/****** Object:  ForeignKey [FK_pubUsuarioLog_pubUsuario]    Script Date: 09/05/2010 01:17:48 ******/
ALTER TABLE [dbo].[pubUsuarioLog]  WITH CHECK ADD  CONSTRAINT [FK_pubUsuarioLog_pubUsuario] FOREIGN KEY([uslUsuario])
REFERENCES [dbo].[pubUsuario] ([usuCodigo])
GO
ALTER TABLE [dbo].[pubUsuarioLog] CHECK CONSTRAINT [FK_pubUsuarioLog_pubUsuario]
GO
/****** Object:  ForeignKey [FK_pubUsuarioLog_pubUsuarioLogAcao]    Script Date: 09/05/2010 01:17:51 ******/
ALTER TABLE [dbo].[pubUsuarioLog]  WITH CHECK ADD  CONSTRAINT [FK_pubUsuarioLog_pubUsuarioLogAcao] FOREIGN KEY([uslAcao])
REFERENCES [dbo].[pubUsuarioLogAcao] ([ulaCodigo])
GO
ALTER TABLE [dbo].[pubUsuarioLog] CHECK CONSTRAINT [FK_pubUsuarioLog_pubUsuarioLogAcao]
GO

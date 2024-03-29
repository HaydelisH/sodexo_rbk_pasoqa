USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosxperfil_no_registrados]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 18/10/2016
-- Descripcion:	Consulta tipos de documentos no grabados según perfil
-- Ejemplo:exec sp_documentosxperfil_no_registrados 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosxperfil_no_registrados]
@ptipousuarioid	INT				-- identificador tipo de uusuario	
	
AS	
BEGIN
	SET NOCOUNT ON;

	SELECT 
	tipodocumentos.idTipoDoc,
	tipodocumentos.NombreTipoDoc ,
	tiposdocumentosxperfil.tipousuarioid
	FROM tipodocumentos
	LEFT JOIN tiposdocumentosxperfil ON  tipodocumentos.idTipoDoc	= tiposdocumentosxperfil.idtipodoc
	                                 AND @ptipousuarioid			= tiposdocumentosxperfil.tipousuarioid
	WHERE tiposdocumentosxperfil.tipousuarioid IS NULL AND TipoDocumentos.Eliminado = 0
	ORDER BY tipodocumentos.NombreTipoDoc
		
	RETURN;
END
GO

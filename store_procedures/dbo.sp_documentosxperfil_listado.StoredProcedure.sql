USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosxperfil_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 8/09/2016
-- Descripcion:	Consulta listado documentos por perfil
-- Ejemplo:exec sp_documentosxperfil_listado  1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosxperfil_listado]
@ptipousuarioid		INT				-- identificador tipo de usuario

	
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
	tiposdocumentosxperfil.tipousuarioid,
	tiposdocumentosxperfil.idTipoDoc,
	tipodocumentos.NombreTipoDoc 
	FROM tiposdocumentosxperfil
	LEFT JOIN tipodocumentos ON tiposdocumentosxperfil.idTipoDoc = tipodocumentos.idTipoDoc 
	WHERE tiposdocumentosxperfil.tipousuarioid = @ptipousuarioid AND tipodocumentos.Eliminado = 0
	ORDER BY tipodocumentos.NombreTipoDoc 
	
	RETURN;
END
GO

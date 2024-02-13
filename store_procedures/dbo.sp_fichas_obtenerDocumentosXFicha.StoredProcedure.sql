USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_obtenerDocumentosXFicha]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 03-06-2019
-- Descripcion:	Obtener una ficha 
-- Ejemplo:exec sp_fichas_obtenerDocumentosXFicha
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_obtenerDocumentosXFicha]
	@pfichaid           INT,
	@pOrigen			INT
AS	
BEGIN
	SET NOCOUNT ON;
	
	IF (@pOrigen  = 1)
		BEGIN
			SELECT 
				 d.documentoid,
				 df.nombrearchivo,
				 f.fichaid,
				 d.tipodocumentoid,
				 TipoGestor.nombre as documento,
				 fd.idTipoSubida
			FROM fichasDatosImportacion f
				INNER JOIN fichasdocumentos fd on f.fichaid = fd.fichaid
				INNER JOIN [Smu_Gestor].[dbo].[documentosinfo] d on fd.documentoid = d.documentoid
				INNER JOIN [Smu_Gestor].[dbo].[documentos] df on d.documentoid = df.documentoid
				INNER JOIN [Smu_Gestor].[dbo].[tiposdocumentos] td on d.tipodocumentoid = td.tipodocumentoid
				INNER JOIN TipoGestor ON TipoGestor.idTipoGestor = td.tipodocumentoid
			WHERE f.fichaid = @pfichaid AND fd.idFichaOrigen = @pOrigen 
		END
	ELSE
		BEGIN 
			SELECT 
				 d.idDocumento as documentoid,
				 d.NombreArchivo  + '.' + d.Extension as nombrearchivo,
				 f.fichaid,
				 tg.idTipoGestor as tipodocumentoid,
				 tg.Nombre as documento,
				 fd.idTipoSubida,
				 c.idplantilla
			FROM fichasDatosImportacion f
				INNER JOIN fichasdocumentos fd on f.fichaid = fd.fichaid
				INNER JOIN Contratos c ON fd.documentoid = c.idDocumento
				INNER JOIN Documentos d ON c.idDocumento = d.idDocumento
				INNER JOIN Plantillas p ON c.idPlantilla = p.idPlantilla
				INNER JOIN TipoGestor tg on p.idTipoGestor = tg.idTipoGestor
			WHERE f.fichaid = @pfichaid AND fd.idFichaOrigen = @pOrigen 
		END
	
	RETURN;
END
GO

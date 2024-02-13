USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_obtenerDocumento]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 04/10/2018
-- Descripcion:	Obtener una ficha 
-- Ejemplo:exec sp_fichas_obtenerDoucmento 
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_obtenerDocumento]
	@fichaid            INT,
	@documentoid		INT,
	@pOrigen			INT
AS	
BEGIN
	SET NOCOUNT ON;
	
	IF( @pOrigen = 1 )
		BEGIN 
			SELECT 
				D.documentoid,
				D.documento,
				D.nombrearchivo As NombreArchivo
			FROM fichasDatosImportacion F
				INNER JOIN fichasdocumentos FD ON F.fichaid = FD.fichaid
				INNER JOIN [Smu_Gestor].[dbo].[documentos] D ON FD.documentoid = D.documentoid
			WHERE F.fichaid = @fichaid AND FD.documentoid = @documentoid AND fd.idFichaOrigen = @pOrigen 
		END
	ELSE
		BEGIN
			SELECT 
				D.idDocumento as documentoid,
				D.documento,
				D.nombrearchivo + '.' + D.Extension As NombreArchivo
			FROM fichasDatosImportacion F
				INNER JOIN fichasdocumentos FD ON F.fichaid = FD.fichaid
				INNER JOIN Documentos D ON fd.documentoid = d.idDocumento
			WHERE F.fichaid = @fichaid AND FD.documentoid = @documentoid AND fd.idFichaOrigen = @pOrigen 
		END
	
	RETURN;
END
GO

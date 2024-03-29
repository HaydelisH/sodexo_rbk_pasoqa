USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerFirmantesPorFirma]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 08-06-2019
-- Descripcion: obtener los firmantes que les corresponde firmar
-- Ejemplo:sp_documentos_obtenerFirmantesPorFirma
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerFirmantesPorFirma]
	@idDocumento INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
    BEGIN
		SELECT 
			UPPER(CF.RutFirmante) As RutFirmante
		FROM
			Contratos C
		INNER JOIN ContratoFirmantes CF on C.idDocumento = CF.idDocumento AND C.idEstado = CF.idEstado AND CF.Firmado = 0
		WHERE 
			C.idDocumento = @idDocumento
	END 
END
GO

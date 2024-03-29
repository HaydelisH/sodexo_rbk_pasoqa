USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_totalFirmantes]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 13/07/2018
-- Descripcion: obtener total de firmantes de un Documento
-- Ejemplo:sp_documentos_totalFirmantes 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_totalFirmantes]
	@idContrato INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	SET NOCOUNT ON;

	DECLARE @total INT   
                         
	SELECT	@total= COUNT(idDocumento)
	FROM  
		ContratoFirmantes
	WHERE
		idDocumento = @idContrato
		AND Firmado=0
    select @total as total
    RETURN                                                             

END
GO

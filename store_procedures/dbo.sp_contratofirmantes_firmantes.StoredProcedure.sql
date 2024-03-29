USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_contratofirmantes_firmantes]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 27/07/2018
-- Descripcion: obtener firmantes de un Documento en un Estado especifico
-- Ejemplo:sp_contratofirmantes_xcontrato 1,2
-- =============================================
CREATE PROCEDURE [dbo].[sp_contratofirmantes_firmantes]
	@idContrato INT,
	@idEstado INT,
	@personaid VARCHAR(10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	SET NOCOUNT ON;

	SELECT 
		UPPER(RutFirmante) As RutFirmante
	FROM 
		ContratoFirmantes 
	WHERE 
		idDocumento = @idContrato 
		AND 
		Firmado = 0 
		AND 
		idEstado = @idEstado
		AND 
		RutFirmante = @personaid
END
GO

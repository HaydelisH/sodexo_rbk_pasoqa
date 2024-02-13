USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_obtenerContratoRenting]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Obtener Empresa
-- Ejemplo:exec sp_empresas_obtener '9798215-5'
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_obtenerContratoRenting]
	@RutEmpresa VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	SELECT 
		COUNT(DV.idDV) AS total
	FROM 
		DocumentosVariables DV
	INNER JOIN 
		Contratos C	
	ON
		C.idContrato = DV.idDocumento
	INNER JOIN 
		TipoDocumentos TD
	ON 
		TD.idTipoDoc = C.idTipoDoc
	WHERE 
		DV.RutCliente = @RutEmpresa
		AND
		C.idTipoDoc = 3                                  
END
GO

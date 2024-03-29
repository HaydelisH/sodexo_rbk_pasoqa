USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_obtenerContratoMarco]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 17/08/2018
-- Descripcion: Consultar si la empresa ya tiene un Contrato Marco
-- Ejemplo:exec sp_empresas_obtenerContratoMarco '9798215-5'
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_obtenerContratoMarco]
	@RutEmpresa VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	BEGIN
		SELECT 
			COUNT(C.idContrato) AS total  
		FROM 
			DocumentosVariables DV
		INNER JOIN 
			Contratos C
		ON
			C.idContrato = DV.idDocumento
		WHERE 
			DV.RutCliente = @RutEmpresa
    END                                        
END
GO

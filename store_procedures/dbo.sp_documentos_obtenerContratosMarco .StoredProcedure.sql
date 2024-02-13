USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerContratosMarco ]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 20/09/2018
-- Descripcion: Obtener los Contratos Marco de esa empresa 
-- Ejemplo: sp_documentos_obtenerContratosMarco '26131316-2'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerContratosMarco ]
	@RutEmpresaC VARCHAR(10)
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
		--Consultar existe la palabra Notario en el registro 			
		SELECT 
			C.idContrato,
			C.idDocumento_Gama,
			CONVERT(VARCHAR(10),C.FechaCreacion,105) AS FechaCreacion,
			E.Descripcion,
			C.idDocumento_Gama
		FROM	
			Contratos C
		INNER JOIN DocumentosVariables DV ON C.idContrato = DV.idDocumento
		INNER JOIN EstadoContratos E ON C.idEstado = E.idEstado
		WHERE
			DV.RutCliente = @RutEmpresaC
			AND
			C.idTipoDoc = 1 
		ORDER BY 
			C.FechaCreacion 
		ASC
				   
	END 
END
GO

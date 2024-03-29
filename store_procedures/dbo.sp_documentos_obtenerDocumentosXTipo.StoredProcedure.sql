USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerDocumentosXTipo]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/11/2018
-- Descripcion: Obtiene los documentos de una empresa y tipo de documento
-- Ejemplo:exec [sp_documentos_obtenerDocumentosXTipo] 'RutEmpresa', 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerDocumentosXTipo]
	@RutEmpresa VARCHAR(10),
	@TipoDoc	INT,
	@ptipousuarioid INT                        -- id del tipo de usuario o perfil
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
    With DocumentosTabla as 
		(
		SELECT 
			CASE idEstado
				WHEN 1 THEN 'Pendiente'
				WHEN 2 THEN 'Proceso'
				WHEN 3 THEN 'Proceso'
				WHEN 4 THEN 'Proceso'
				WHEN 5 THEN 'Proceso'
				WHEN 8 THEN 'Rechazados'
				WHEN 6 THEN 'Firmados'
				
			END AS Estado, 
			COUNT(idContrato) As Cantidad 
		FROM 
			Contratos C
			INNER JOIN DocumentosVariables DV	ON DV.idDocumento = C.idContrato
			INNER JOIN accesodocxperfillugarespago AccLP ON DV.RutCliente = AccLP.lugarpagoid 
														AND DV.RutEmpresa = AccLP.empresaid
														AND AccLP.tipousuarioid = @ptipousuarioid
		WHERE 
			DV.RutEmpresa = @RutEmpresa AND c.idTipoDoc  = @TipoDoc
			Group by idEstado 
		)				
		select Estado,Sum(Cantidad) as Total  from DocumentosTabla
				Group By  Estado 	
	END
END
GO

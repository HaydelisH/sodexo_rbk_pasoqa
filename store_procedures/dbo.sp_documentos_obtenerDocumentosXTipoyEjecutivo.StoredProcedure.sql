USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerDocumentosXTipoyEjecutivo]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 10/11/2018
-- Descripcion: Obtiene los documentos de una empresa, por tipo de documento y ejecutivo
-- Ejemplo:exec [sp_documentos_obtenerDocumentosXTipoyEjecutivo] 'RutEmpresa', 1,'12123123-1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerDocumentosXTipoyEjecutivo]
	@RutEmpresa VARCHAR(10),
	@RutEjecutivo VARCHAR(10),
	@TipoDoc	INT,
	@ptipousuarioid INT                       -- id del tipo de usuario o perfil
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
			INNER JOIN Ejecutivos E				ON E.RutCliente = DV.RutCliente
			INNER JOIN accesodocxperfillugarespago AccLP ON DV.RutCliente = AccLP.lugarpagoid 
														AND DV.RutEmpresa = AccLP.empresaid
														AND AccLP.tipousuarioid = @ptipousuarioid
		WHERE 
			DV.RutEmpresa = @RutEmpresa AND c.idTipoDoc  = @TipoDoc AND E.RutEjecutivo = @RutEjecutivo
			Group by idEstado 
		)				
		select Estado,Sum(Cantidad) as Total  from DocumentosTabla
				Group By  Estado 	
	END
END
GO

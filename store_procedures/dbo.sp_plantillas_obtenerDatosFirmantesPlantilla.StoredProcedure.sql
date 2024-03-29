USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerDatosFirmantesPlantilla]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Descripcion: Obtiene llos datos de firmantes segun el flujo de la plantilla
-- Ejemplo:exec sp_plantillas_obtenerDatosFirmantesPlantilla 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerDatosFirmantesPlantilla]
	@idPlantilla INT
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
		IF EXISTS (SELECT idPlantilla FROM Plantillas WHERE (idPlantilla=@idPlantilla AND Eliminado=0))
			BEGIN
				SELECT
					P.idWF,
 					CE.idEstado,
 					CE.Descripcion,
 					WEP.ConOrden
				FROM
					Plantillas P
				INNER JOIN
					WorkflowProceso WF
				ON
					P.idWF = WF.idWF 
				INNER JOIN 
					WorkflowEstadoProcesos WEP
				ON
					WEP.idWorkflow = WF.idWF
				INNER JOIN 
					ContratosEstados CE
				ON 
					CE.idEstado = WEP.idEstadoWF
				WHERE	
					P.Eliminado = 0
					AND
					P.idPlantilla = @idPlantilla      
			END 
	END
END
GO

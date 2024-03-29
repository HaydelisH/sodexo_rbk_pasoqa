USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantes_obtenerEstados]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_firmantes_obtenerEstados]
	@idWF INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
			
    -- Insert statements for procedure here
    BEGIN
		--Consultar existe la palabra Notario en el registro 			
		SELECT 
			WFP.idEstadoWF,
			WFP.Orden,
			E.Descripcion As Nombre,
			E.idEstado,
			WFP.ConOrden
		FROM 
			WorkflowEstadoProcesos WFP
		INNER JOIN 
			ContratosEstados E
		ON
			WFP.idEstadoWF = E.idEstado
		WHERE
			idWorkflow = @idWF   
	END 
END
GO

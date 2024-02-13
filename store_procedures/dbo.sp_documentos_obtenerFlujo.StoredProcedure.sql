USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerFlujo]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 03/07/2018
-- Descripcion: Verificar si el flujo tiene Notario
-- Ejemplo:sp_documentos_modificar 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerFlujo]
	@idWF INT
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
			EW.idEstado,
			EW.Nombre
		FROM WorkflowEstadoProcesos WP 
		INNER JOIN EstadosWorkflow        EW ON EW.idEstado = WP.idEstadoWF
		WHERE WP.idWorkflow = @idWF  
	END 
END
GO

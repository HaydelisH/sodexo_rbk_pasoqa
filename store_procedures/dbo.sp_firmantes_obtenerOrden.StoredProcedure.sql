USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantes_obtenerOrden]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/07/2018
-- Descripcion: Obtener orden de un firmante 
-- Ejemplo:sp_firmantes_obtenerOrden 1,2
-- =============================================
CREATE PROCEDURE [dbo].[sp_firmantes_obtenerOrden]
	@idWF INT,
	@idEstado INT
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
			Orden
		FROM 
			WorkflowEstadoProcesos 
		WHERE 
			idWorkflow = @idWF 
			AND 
			idEstadoWF = @idEstado			   
	END 
END
GO

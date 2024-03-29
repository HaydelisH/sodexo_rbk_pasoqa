USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_procesos_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04-04-2019
-- Descripcion:  Actualiza los datos de un Proceso
-- Ejemplo:exec sp_procesos_modificar 'modificar',1,'ejemplo'
-- =============================================
CREATE PROCEDURE [dbo].[sp_procesos_obtener]
	@idProceso INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here

	SELECT 
		idProceso,
		Descripcion
	FROM 
		Procesos 
	WHERE 
		idProceso = @idProceso AND Eliminado = 0
		
END
GO

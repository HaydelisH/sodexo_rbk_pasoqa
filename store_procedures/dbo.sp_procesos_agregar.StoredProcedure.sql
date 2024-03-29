USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_procesos_agregar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04-04-2019
-- Descripcion:  Agregar un Proceso
-- Ejemplo:exec sp_caprocesos_agregar 'agregar', 'ejemplo'
-- =============================================
CREATE PROCEDURE [dbo].[sp_procesos_agregar]
	@pAccion CHAR(60),
	@Descripcion VARCHAR(50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		INSERT INTO Procesos(Descripcion, Eliminado) VALUES (@Descripcion, 0) 
		SELECT @@IDENTITY AS idProceso
    END    
END
GO

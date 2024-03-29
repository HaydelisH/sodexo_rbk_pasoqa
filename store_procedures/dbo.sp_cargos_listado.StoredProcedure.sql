USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_cargos_listado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernanddez
-- Creado el: 29-03-2019
-- Descripcion: Listado de los Cargos disponibles
-- Ejemplo: sp_cargos_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_cargos_listado]

AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
			
    -- Insert statements for procedure here

	SELECT 
		idCargo,
		Descripcion
	FROM
		Cargos
	WHERE 
		Eliminado = 0
		
END
GO

USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tipogestor_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 15-04-2019
-- Descripcion:  Mostrar listado de los Tipo de documento del gestor 
-- Ejemplo:exec sp_tipogestor_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_tipogestor_listado]

AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
  
	SELECT 
		idTipoGestor,
		Nombre
	FROM 
		TipoGestor 
	ORDER BY 
		Nombre 
	ASC
		
END
GO

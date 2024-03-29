USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_procesos_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04-04-2019
-- Descripcion:  Obtiene todos los procesos disponibles
-- Ejemplo:exec sp_procesos_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_procesos_listado]
AS
BEGIN
	
	SELECT 
		idProceso, 
		Descripcion,
		idTipoMovimiento,
		CASE WHEN idTipoMovimiento IS NULL THEN 0
		ELSE 1 END As Automatica
	FROM 
		Procesos
	WHERE 
		Eliminado = 0 
		
    RETURN                                                             
END
GO

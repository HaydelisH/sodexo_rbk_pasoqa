USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_listado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29-04-2019
-- Descripcion: Listar todos los centros de costo 
-- Ejemplo:exec sp_centrocosto_gestor_agregar
-- =============================================
CREATE PROCEDURE [dbo].[sp_centroscosto_listado]
AS	
BEGIN
	SET NOCOUNT ON;

	SELECT 
		centrocostoid As idCentroCosto,
		nombrecentrocosto As Descripcion
	FROM 
		centroscosto
		
END
GO

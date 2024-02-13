USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_EstadosPostulante_obtenerTodo]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/14/2018
-- Descripcion: Obtiene el nombre de persona
-- Ejemplo:exec sp_EstadosPostulante_obtenerTodo
-- =============================================
CREATE PROCEDURE [dbo].[sp_EstadosPostulante_obtenerTodo]
	--@empresaid VARCHAR (10)
AS
BEGIN
	
    SELECT 
		EstadosPostulante.estadoPostulanteid, 
		EstadosPostulante.nombre
	FROM EstadosPostulante 
    --WHERE empresaid = @empresaid
    RETURN                                                             

END
GO

USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_disponibilidad_listar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/14/2018
-- Descripcion: Obtiene el nombre de persona
-- Ejemplo:exec sp_disponibilidad_listar
-- =============================================
CREATE PROCEDURE [dbo].[sp_disponibilidad_listar]
	--@empresaid VARCHAR (10)
AS
BEGIN
	
    SELECT 
		disponibilidad.disponibilidadid, 
		disponibilidad.nombre
	FROM disponibilidad 
   -- WHERE empresaid = @empresaid
    RETURN                                                             

END
GO

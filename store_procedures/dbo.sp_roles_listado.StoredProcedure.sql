USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_roles_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 30-04-2019
-- Descripcion:  Listado de roles disponibles
-- Ejemplo:exec sp_roles_
-- =============================================
CREATE PROCEDURE [dbo].[sp_roles_listado]
AS
BEGIN
	
	SELECT 
		rolid, 
		Descripcion 
	FROM 
		Roles

		
    RETURN                                                             
END
GO

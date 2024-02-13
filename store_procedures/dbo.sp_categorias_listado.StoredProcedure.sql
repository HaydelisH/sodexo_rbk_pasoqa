USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_categorias_listado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/28/2018
-- Descripcion:  Obtiene todas las categorias disponibles
-- Ejemplo:exec sp_categorias_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_categorias_listado]
AS
BEGIN
	
    SELECT idCategoria,Descripcion, Titulo FROM Categorias WHERE Eliminado=0
                         
    RETURN                                                             

END
GO

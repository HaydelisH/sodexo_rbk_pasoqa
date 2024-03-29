USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_listado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Muestra el listado de Clausulas que esten registradas 
-- Ejemplo:exec sp_clausulas_listado 
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_listado]
AS
BEGIN
	
    SELECT Clausulas.idClausula, Clausulas.Titulo_Cl, Clausulas.Descripcion_Cl, Categorias.Titulo, 
Clausulas.Aprobado FROM Clausulas INNER JOIN Categorias ON Clausulas.idCategoria=Categorias.idCategoria
WHERE Clausulas.Eliminado=0
                         
    RETURN                                                             

END
GO

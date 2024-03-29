USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_categorias_total]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/28/2018
-- Descripcion: Muestra la cantidad de la cantidad total de registros
-- Ejemplo:exec sp_categorias_total
-- =============================================
CREATE PROCEDURE [dbo].[sp_categorias_total]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	SET NOCOUNT ON;
	
	DECLARE @total INT
    
    SELECT @total= COUNT(idCategoria)+1 FROM Categorias
                         
    select @total as total
    RETURN                                                             

END
GO

USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_total]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Obtiene la cantidad de Clausulas registradas
-- Ejemplo:exec sp_clausulas_total
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_total]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	SET NOCOUNT ON;

	DECLARE @total INT
    
    SELECT @total= COUNT(idClausula)+1 FROM Clausulas
                         
    select @total as total
    RETURN                                                             

END
GO

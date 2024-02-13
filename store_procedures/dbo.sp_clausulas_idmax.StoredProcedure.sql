USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_idmax]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Obtiene el Id de Clausula de mayor valor
-- Ejemplo:exec sp_clausulas_idmax 
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_idmax]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
   SELECT @total = MAX(idClausula) FROM Clausulas
   SET @total = @total + 1
  
   SELECT @total AS total
END
GO

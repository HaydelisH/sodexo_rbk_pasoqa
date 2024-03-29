USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_idmax]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtiene el id de mayor valor de Plantillas 
-- Ejemplo:exec sp_plantillas_idmax
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_idmax]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
   SELECT @total = MAX(idPlantilla) FROM Plantillas
   SET @total = @total + 1
  
   SELECT @total AS total
END
GO

USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_feriados_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Obtiene los datos solicitados en este caso Descripcion
-- =============================================
CREATE PROCEDURE [dbo].[sp_feriados_obtener]
	@idFeriado INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;			
    -- Insert statements for procedure here
   
  SELECT 
  idFeriado,
  CONVERT(VARCHAR(10),Fecha,105)AS Fecha, 
  Descripcion 
  FROM Feriados 
  WHERE idFeriado = @idFeriado
		
END
GO

USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_feriados_obtener_xfecha]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 23/07/2018
-- Descripcion: obtiene dato feriado según fecha 
-- ejemplo : sp_feriados_obtener_xfecha '2018-07-23'
-- =============================================
CREATE PROCEDURE [dbo].[sp_feriados_obtener_xfecha]
	@pfecha DATE
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
  WHERE Fecha = @pfecha
		
END
GO

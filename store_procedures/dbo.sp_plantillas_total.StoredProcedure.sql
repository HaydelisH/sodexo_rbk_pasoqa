USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_total]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtiene la cantidad de Plantillas registradas 
-- Ejemplo:exec sp_plantillas_total
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_total]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	SET NOCOUNT ON;

	DECLARE @total INT
    
    SELECT @total= COUNT(idPlantilla) FROM Plantillas
                         
    select @total as total
    RETURN                                                             

END
GO

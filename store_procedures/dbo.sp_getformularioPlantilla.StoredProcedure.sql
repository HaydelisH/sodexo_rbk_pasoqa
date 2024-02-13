USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_getformularioPlantilla]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Modificado: gdiaz 14/04/2021
-- Ejemplo:exec [sp_getformularioPlantilla] 
-- =============================================
--NUEVO
CREATE PROCEDURE [dbo].[sp_getformularioPlantilla]
	@idFormulario INT
AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT 
        formularioPlantilla.idPlantilla
    FROM 
	    formularioPlantilla
    WHERE 
            formularioPlantilla.eliminado = 0
        AND
            formularioPlantilla.idFormulario = @idFormulario
	
END
GO

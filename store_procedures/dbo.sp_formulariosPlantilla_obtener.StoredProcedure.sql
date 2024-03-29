USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_formulariosPlantilla_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_formulariosPlantilla_obtener] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_formulariosPlantilla_obtener]
	--@usuarioid INT
AS
BEGIN	
	SET NOCOUNT ON;			
 
	SELECT 
        formularioPlantilla.opcionid,
        formularioPlantilla.oculta
    FROM 
	    formularioPlantilla
    WHERE 
        formularioPlantilla.eliminado = 0
	
END
GO

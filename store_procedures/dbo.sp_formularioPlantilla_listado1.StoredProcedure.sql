USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_formularioPlantilla_listado1]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Modificado: gdiaz 14/04/2021
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_formularioPlantilla_listado1] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_formularioPlantilla_listado1]
AS
BEGIN	
	SET NOCOUNT ON;			
 
    SELECT
		formularioPlantilla.idFormulario,
        formularioPlantilla.nombreFormulario
    FROM formularioPlantilla
END
GO
